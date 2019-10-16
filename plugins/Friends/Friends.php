<?php
/**
* Friends Plugin Main File
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version 1.0.0
*/

use Helpers\{ErrorMessages,Paginator,SuccessMessages};

/** Get data from URL **/
(empty($viewVars[0])) ? $get_var_1 = "" : $get_var_1 = $viewVars[0];
(empty($viewVars[1])) ? $get_var_2 = "" : $get_var_2 = $viewVars[1];
(empty($viewVars[2])) ? $get_var_3 = "" : $get_var_3 = $viewVars[2];
(empty($viewVars[3])) ? $get_var_4 = "" : $get_var_4 = $viewVars[3];

/** Set the Plugin Directory **/
$plugin_dir = CUSTOMDIR.'plugins/Friends/';

/** Load Messages Plugin Models **/
require($plugin_dir.'class.Friends.php');
$FriendsModel = new Friends();
$pages = new Paginator(MESSAGE_PAGEINATOR_LIMIT);

/** If User Not logged in - kick them out **/
if (!$auth->isLogged())
  ErrorMessages::push(Language::show('user_not_logged_in', 'Auth'), 'Login');

/** Include the Sidebar **/
require($plugin_dir.'pages/friends_sidebar.php');

/** Get the Plugin Page and Display it **/
if($get_var_1 == 'Requests'){
  /** Include the Messages List File **/
  require($plugin_dir.'pages/friend_requests.php');
}else if($get_var_1 == 'UnFriend'){
  /** Get friend's userID **/
  $username = $get_var_2;
  $friend_id = $FriendsModel->getFriendID($username);
  if(isset($friend_id)){
     /** Check to make sure user is currently a friend **/
     if($FriendsModel->check_friend($currentUserData[0]->userID, $friend_id)){
         /** Remove friend from friends list **/
         if($FriendsModel->unfriend($currentUserData[0]->userID, $friend_id)){
             /* Success Message Display */
             SuccessMessages::push($username.' has been removed from your friends list!', 'Friends');
         }else{
             /* Error Message Display */
             ErrorMessages::push('Friend was NOT removed!', 'Friends');
         }
     }else{
         /* Error Message Display */
         ErrorMessages::push($username.' is not your friend!', 'Friends');
     }
  }else{
     /* Error Message Display */
     \Libs\ErrorMessages::push('Friend ID not found!', 'Friends');
  }
}else if($get_var_1 == 'AddFriend'){
  /** Get friend's userID **/
  $username = $get_var_2;
  $friend_id = $FriendsModel->getFriendID($username);
  if(isset($friend_id)){
      /** Check to make sure user is currently a friend **/
      if($FriendsModel->check_friend($currentUserData[0]->userID, $friend_id)){
          /* Error Message Display */
          ErrorMessages::push($username.' is already your friend, or has yet to approve your request!', 'Friends');
      }else{
          /** Remove friend from friends list **/
          if($FriendsModel->addfriend($currentUserData[0]->userID, $friend_id)){
              /* Success Message Display */
              SuccessMessages::push('Friend Request Sent to '.$username.'!', 'Friends/Requests/');
          }else{
              /* Error Message Display */
              ErrorMessages::push('Friend Request NOT sent!', 'Friends/Requests/');
          }
      }
  }else{
      /* Error Message Display */
      ErrorMessages::push('Friend ID not found!', 'Friends/Requests/');
  }
}else if($get_var_1 == "ApproveFriend"){
  /** Get friend's userID **/
  $username = $get_var_2;
  $friend_id = $FriendsModel->getFriendID($username);
  if(isset($friend_id)){
      /** Check to make sure user is currently a friend **/
      if($FriendsModel->check_friend($currentUserData[0]->userID, $friend_id)){
          /* Error Message Display */
          ErrorMessages::push($username.' is already your friend!', 'Friends');
      }else{
          /** Remove friend from friends list **/
          if($FriendsModel->approvefriend($currentUserData[0]->userID, $friend_id)){
              /* Success Message Display */
              SuccessMessages::push('You have approved a friend request with '.$username.'!', 'Friends/Requests/');
          }else{
              /* Error Message Display */
              ErrorMessages::push('Friend Request NOT approved!', 'Friends/Requests/');
          }
      }
  }else{
      /* Error Message Display */
      ErrorMessages::push('Friend ID not found!', 'Friends/Requests/');
  }
}else if($get_var_1 == "CancelFriend"){
  /** Get friend's userID **/
  $username = $get_var_2;
  $friend_id = $FriendsModel->getFriendID($username);
  if(isset($friend_id)){
      /** Remove friend from friends list **/
      if($FriendsModel->unfriend($currentUserData[0]->userID, $friend_id)){
          /* Success Message Display */
          SuccessMessages::push('You have canceled a friend request with '.$username , 'Friends/Requests/');
      }else{
          /* Error Message Display */
          ErrorMessages::push('Friend was NOT removed!', 'Friends/Requests/');
      }
  }else{
      /* Error Message Display */
      ErrorMessages::push('Friend ID not found!', 'Friends/Requests/');
  }
}else{
  /** Include the Messages Home File **/
  require($plugin_dir.'pages/friends.php');
}

/** Include the Sidebar **/
require($plugin_dir.'pages/friends_sidebar_right.php');

?>
