<?php
/**
* Friends Plugin Friend Requests List Page
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version 1.0.0
*/


/** Collect Data page for view **/
$data['title'] = "My Friend Requests";
$data['welcomeMessage'] = "Welcome to Your Friend Requests List";

/** Setup Breadcrumbs **/
$data['breadcrumbs'] = "<li class='breadcrumb-item active'>My Friend Requests</li>";

/** Setup form token! **/
$data['csrf_token'] = Csrf::makeToken('friends');

/** Get List of All Current User's Sent Friend Requests **/
$friends_requests_recv = $FriendsModel->friends_requests_recv($currentUserData[0]->userID);

/** Get List of All Current User's Sent Friend Requests **/
$friends_requests_sent = $FriendsModel->friends_requests_sent($currentUserData[0]->userID);

?>

<div class="col-lg-9 col-md-8 col-sm-12">
	<div class="card mb-3">
		<div class="card-header h4">
			Pending Received Friend Requests
		</div>
    <table class="table table-striped table-hover responsive">
            <?php
                /** Check to make sure current user has friends **/
                if(!empty($friends_requests_recv)){
                    echo "<thead><tr>
                            <th colspan='2'>User Name</th>
                            <th>First Name</th>
                            <th colspan='3'>Actions</th>
                          </tr></thead>";
                    echo "<tbody>";
                    foreach ($friends_requests_recv as $var) {
                        /** Make sure we are showing friend and not current user **/
                        if($var->uid1 == $currentUserData[0]->userID){
                            $friend_id = $var->uid2;
                        }else if($var->uid2 == $currentUserData[0]->userID){
                            $friend_id = $var->uid1;
                        }
                        /** Check to make sure there is a friend id **/
                        if(isset($friend_id)){
                            $member_username = CurrentUserData::getUserName($friend_id);
                            $member_firstName = CurrentUserData::getUserFirstName($friend_id);
                            $member_userImage = CurrentUserData::getUserImage($friend_id);
                            $online_check = CurrentUserData::getUserStatusDot($friend_id);
                            echo "<tr>
                                    <td width='20px'><img src=".SITE_URL.IMG_DIR_PROFILE.$member_userImage." class='rounded' style='height: 25px'></td>
                                    <td> $online_check <a href='".SITE_URL."Profile/{$member_username}'> {$member_username}</a></td>
                                    <td>{$member_firstName}</td>
                                    <td><a href='".SITE_URL."Profile/{$member_username}' class='btn btn-sm btn-primary'>View Profile</a></td>
                                    <td><a href='".SITE_URL."Friends/ApproveFriend/{$member_username}' class='btn btn-sm btn-success'>Approve</a></td>
                                    <td><a href='".SITE_URL."Friends/CancelFriend/{$member_username}' class='btn btn-sm btn-danger'>Reject Request</a></td>
                                  </tr>";
                        }
                    }
                    echo "</tbody>";
                }else{
                  echo "<tbody>";
                      echo "<tr><th colspan='5'>You don't have any pending friend requests.</th></tr>";
                  echo "</tbody>";
                }
            ?>
    </table>
  </div>

	<div class="card mb-3">
		<div class="card-header h4">
			Pending Sent Friend Requests
		</div>
    <table class="table table-striped table-hover responsive">
            <?php
                /** Check to make sure current user has friends **/
                if(!empty($friends_requests_sent)){
                    echo "<thead><tr>
                            <th colspan='2'>User Name</th>
                            <th>First Name</th>
                            <th colspan='2'>Actions</th>
                          </tr></thead>";
                    echo "<tbody>";
                    foreach ($friends_requests_sent as $var) {
                        /** Make sure we are showing friend and not current user **/
                        if($var->uid1 == $currentUserData[0]->userID){
                            $friend_id = $var->uid2;
                        }else if($var->uid2 == $currentUserData[0]->userID){
                            $friend_id = $var->uid1;
                        }
                        /** Check to make sure there is a friend id **/
                        if(isset($friend_id)){
                            $member_username = CurrentUserData::getUserName($friend_id);
                            $member_firstName = CurrentUserData::getUserFirstName($friend_id);
                            $member_userImage = CurrentUserData::getUserImage($friend_id);
                            $online_check = CurrentUserData::getUserStatusDot($friend_id);
                            echo "<tr>
                                    <td width='20px'><img src=".SITE_URL.IMG_DIR_PROFILE.$member_userImage." class='rounded' style='height: 25px'></td>
                                    <td> $online_check <a href='".SITE_URL."Profile/{$member_username}'> {$member_username}</a></td>
                                    <td>{$member_firstName}</td>
                                    <td><a href='".SITE_URL."Profile/{$member_username}' class='btn btn-sm btn-primary'>View Profile</a></td>
                                    <td><a href='".SITE_URL."CancelFriend/{$member_username}' class='btn btn-sm btn-danger'>Cancel</a></td>
                                  </tr>";
                        }
                    }
                    echo "</tbody>";
                }else{
                    echo "<tbody>";
                        echo "<tr><th colspan='5'>You don't have any pending friend requests.</th></tr>";
                    echo "</tbody>";
                }
            ?>
    </table>
  </div>
</div>
