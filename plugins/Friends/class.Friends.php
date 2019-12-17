<?php
/**
* UserCandy Friends Models Plugin
*
* UserCandy - Friends Plugin
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.3
*/

use Core\Models;
use Helpers\Mail;

/** Friends model **/
class Friends extends Models {

    /**
    * friends_list
    *
    * get list of all friends for current user.
    *
    * @param int current userID
    *
    * @return array returns all friends
    */
    public function friends_list($userID, $orderby = "", $limit, $search = null){
        /** Set default orderby if one is not set **/
        if($orderby == "UN-DESC"){
          $run_order = "u.username DESC";
        }else if($orderby == "UN-ASC"){
          $run_order = "u.username ASC";
        }else{
          // Default order
          $run_order = "u.userID ASC";
        }
        /** Check to see if user is searching for a friend **/
        if(isset($search)){
            $data = $this->db->select("
                SELECT
                    u.userID,
                    u.username,
                    u.firstName,
                    u.lastName,
                    f.*
                FROM
                    ".PREFIX."plugin_friends f
                LEFT JOIN
                    ".PREFIX."users u
                    ON (u.userID = f.uid1 OR u.userID = f.uid2)
                WHERE
                (
                    f.uid1 = :userID
                OR
                    f.uid2 = :userID
                )
                AND
                (
                    f.status1 = 1 AND f.status2 = 1
                )
                AND
                    u.userID != :userID
                AND
                    u.isactive = 1
                AND
                    (u.username LIKE :search OR u.firstName LIKE :search OR u.lastName LIKE :search)
                GROUP BY
                    u.username
                ORDER BY
                    $run_order
                    $limit
            ",
            array(':userID' => $userID, ':search' => '%'.$search.'%'));
        }else{
            $data = $this->db->select("
                SELECT
                    u.userID,
                    u.username,
                    f.*
                FROM
                    ".PREFIX."plugin_friends f
                LEFT JOIN
                    ".PREFIX."users u
                    ON (u.userID = f.uid1 OR u.userID = f.uid2)
                WHERE
                (
                    f.uid1 = :userID
                OR
                    f.uid2 = :userID
                )
                AND
                (
                    f.status1 = 1 AND f.status2 = 1
                )
                AND
                    u.userID != :userID
                AND
                    u.isactive = 1
                GROUP BY
                    u.username
                ORDER BY
                    $run_order
                    $limit
            ",
            array(':userID' => $userID));
        }
        return $data;
    }

    /**
    * friends_requests_recv
    *
    * get list of all friend requests recived for current user.
    *
    * @param int current userID
    *
    * @return array returns all friend requests recived
    */
    public function friends_requests_recv($userID){
        $data = $this->db->select("
            SELECT
                *
            FROM
                ".PREFIX."plugin_friends
            WHERE
                uid2 = :userID
            AND
            (
                status1 = 1 AND status2 = 0
            )
            GROUP BY
                uid1, uid2
            ORDER BY
                id
            ASC
        ",
        array(':userID' => $userID));
        return $data;
    }

    /**
    * friends_requests_sent
    *
    * get list of all friend requests sent by current user that need approved.
    *
    * @param int current userID
    *
    * @return array returns all friend requests sent
    */
    public function friends_requests_sent($userID){
        $data = $this->db->select("
            SELECT
                *
            FROM
                ".PREFIX."plugin_friends
            WHERE
                uid1 = :userID
            AND
            (
                status1 = 1 AND status2 = 0
            )
            GROUP BY
                uid1, uid2
            ORDER BY
                id
            ASC
        ",
        array(':userID' => $userID));
        return $data;
    }

    /**
    * check_friend
    *
    * check if requested friend and current user are friends.
    *
    * @param int current userID
    * @param int friends userID
    *
    * @return booleen returns true/false
    */
    public function check_friend($userID, $friend_id){
        $count = count($this->db->select("
            SELECT
                *
            FROM
                ".PREFIX."plugin_friends
            WHERE
            (
                (uid1 = :userID AND uid2 = :friend_id)
            OR
                (uid2 = :userID AND uid1 = :friend_id)
            )
            AND
            (
                status1 = 1 AND status2 = 1
            )
            GROUP BY
                uid1, uid2
            ORDER BY
                id
            ASC
        ",
        array(':userID' => $userID, ':friend_id' => $friend_id)));
        if($count > 0){
            return true;
        }else{
            return false;
        }

    }

    /**
    * unfriend
    *
    * removes requested friend for current user.
    *
    * @param int current userID
    * @param int friends userID
    *
    * @return booleen returns true/false
    */
    public function unfriend($userID, $friend_id){
        $data_a = $this->db->delete(PREFIX.'plugin_friends', array('uid1' => $userID, 'uid2' => $friend_id));
        $data_b = $this->db->delete(PREFIX.'plugin_friends', array('uid2' => $userID, 'uid1' => $friend_id));
        $count = $data_a + $data_b;
        if($count > 0){
          return true;
        }else{
          return false;
        }
    }

    /**
    * getFriendID
    *
    * gets friends userID.
    *
    * @param string friends username
    *
    * @return int returns friends userID
    */
    public function getFriendID($friend_un){
        $user_data = $this->db->select("
				SELECT
					userID
				FROM
					".PREFIX."users u
				WHERE
				    username = :friend_un
                LIMIT 1
				",
			array(':friend_un' => $friend_un));
		return $user_data[0]->userID;
    }

    /**
    * addfriend
    *
    * sends friend request to given user by current user.
    *
    * @param int current userID
    * @param int friends userID
    *
    * @return booleen returns true/false
    */
    public function addfriend($userID, $friend_id){
        $data = $this->db->insert(PREFIX.'plugin_friends', array('uid1' => $userID, 'uid2' => $friend_id, 'status1' => '1'));
        if($data > 0){
            /* Email the user and let them know that they have a friend request */
            // Get to user data
            $email_data = $this->db->select("
              SELECT email, username
              FROM ".PREFIX."users
              WHERE userID = :where_id
              LIMIT 1
            ",
            array(':where_id' => $friend_id));
            // Get from user data
            $email_from_data = $this->db->select("
              SELECT username
              FROM ".PREFIX."users
              WHERE userID = :where_id
              LIMIT 1
            ",
            array(':where_id' => $userID));
            //EMAIL MESSAGE USING PHPMAILER
            $mail = new Helpers\Mail();
            $mail->setFrom(SITEEMAIL, EMAIL_FROM_NAME);
            $mail->addAddress($email_data[0]->email);
            $mail_subject = SITE_TITLE . " - Friends - ".$email_from_data[0]->username." sent you a Friend Request";
            $mail->subject($mail_subject);
            $body = "Hello ".$email_data[0]->username."<br/><br/>";
            $body .= SITE_TITLE . " - New Friend Request Notification
                                  <hr/>
                                  ".$email_from_data[0]->username." wants to be your friend on ".SITE_TITLE."
                                  <hr/>";
            $body .= "You may approve or reject at: <b><a href=\"" . SITE_URL . "/\">" . SITE_TITLE . "</a></b>";
            $mail->body($body);
            $mail->send();

            return true;
        }else{
            return false;
        }
    }

    /**
    * approvefriend
    *
    * approves friend request with set user with current user.
    *
    * @param int current userID
    * @param int friends userID
    *
    * @return booleen returns true/false
    */
    public function approvefriend($userID, $friend_id){
        $data = $this->db->update(PREFIX.'plugin_friends', array('status2' => '1'), array('uid1' => $friend_id, 'uid2' => $userID));
        if($data > 0){
            return true;
        }else{
            return false;
        }
    }

    /**
    * getFriendsCountSearch
    *
    * gets count of friends that match search.
    *
    * @param int current userID
    * @param int friends userID
    *
    * @return booleen returns true/false
    */
    public function getFriendsCountSearch($userID, $search){
        $data = $this->db->select("
            SELECT
                u.userID,
                u.username,
                u.firstName,
                u.lastName,
                f.*
            FROM
                ".PREFIX."plugin_friends f
            LEFT JOIN
                ".PREFIX."users u
                ON (u.userID = f.uid1 OR u.userID = f.uid2)
            WHERE
            (
                f.uid1 = :userID
            OR
                f.uid2 = :userID
            )
            AND
            (
                f.status1 = 1 AND f.status2 = 1
            )
            AND
                u.userID != :userID
            AND
                (u.username LIKE :search OR u.firstName LIKE :search OR u.lastName LIKE :search)
            GROUP BY
                u.username
        ",
        array(':userID' => $userID, ':search' => '%'.$search.'%'));
        return count($data);
    }

    /**
    * get list of all friends for current user.
    * @param int current $userID
    * @param int $limit
    * @return array dataset
    */
    public function getFriendsIDs($userID, $limit = '100'){
        /** Get list of User's friends and return their UserIDs **/
            $data = $this->db->select("
                SELECT
                    u.userID
                FROM
                    ".PREFIX."plugin_friends f
                LEFT JOIN
                    ".PREFIX."users u
                    ON (u.userID = f.uid1 OR u.userID = f.uid2)
                WHERE
                (
                    f.uid1 = :userID
                OR
                    f.uid2 = :userID
                )
                AND
                (
                    f.status1 = 1 AND f.status2 = 1
                )
                AND
                    u.userID != :userID
                AND
                    u.isactive = 1
                GROUP BY
                    u.username
                LIMIT $limit
            ",
            array(':userID' => $userID));
        return $data;
    }

    /**
    * get list of users not friends with current user, but friends of friends.
    * @param int current userID
    * @return array dataset
    */
    public function getSuggestedFriends($userID){
        /** Get list of User's friends and return their UserIDs **/
        $friends = SELF::getFriendsIDs($userID);
        $suggestedFriends = [];
        /** Clean up Friends array to only userIDs **/
        foreach ($friends as $friend) {
          $user_friends[] = $friend->userID;
        }
        foreach ($friends as $friendId) {
          /** Friends friends list. **/
          $ff_list = SELF::getFriendsIDs($friendId->userID);
          foreach ($ff_list as $ffriendId) {
            /** If the friendsFriend(ff) is not us, and not our friend, he can be suggested **/
            if ($ffriendId->userID != $userID && !in_array($ffriendId->userID, $user_friends)) {
              /** The key is the suggested friend **/
              $suggestedFriends[$ffriendId->userID] = ['mutual_friends' => []];
              $ff_friends = SELF::getFriendsIDs($ffriendId->userID);
              foreach ($ff_friends as $ff_friendId) {
                /** If the friendsFriend(ff) is not us, and not our friend, he can be suggested **/
                if($ff_friendId->userID != $userID){
                  /** If he is a friend of the current user, he is a mutual friend **/
                  $suggestedFriends[$ffriendId->userID]['mutual_friends'] = count(SELF::getMutualFriendsIDs($userID, $ffriendId->userID));
                }
              }
            }
          }
        }
        return $suggestedFriends;
    }

    /**
    * get list of users not friends with current user, but friends of friends.
    * @param int $userID
    * @param int $cur_userID
    * @return array dataset
    */
    public function getMutualFriendsIDs($userID, $cur_userID){
        /** Get list of User's friends and return their UserIDs **/
        $friends = SELF::getFriendsIDs($userID);
        $user_friends = [];
        /** Clean up Friends array to only userIDs **/
        foreach ($friends as $friend) {
          $user_friends[] = $friend->userID;
        }
        $mutual_friends = [];
        $ff_list = SELF::getFriendsIDs($cur_userID);
        foreach ($ff_list as $ffriendId) {
          /** If the friendsFriend(ff) is not us, and our friend, he can be mutual **/
          if ($ffriendId->userID != $userID && $ffriendId->userID != $cur_userID && in_array($ffriendId->userID, $user_friends)) {
            $mutual_friends[] = $ffriendId->userID;
          }
        }
        return $mutual_friends;
    }

}
