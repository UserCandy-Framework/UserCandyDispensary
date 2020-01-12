<?php
/**
* Friends Plugin Friends Sidebar
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.4
*/

use Helpers\CurrentUserData;

$data['suggested_friends'] = $FriendsModel->getSuggestedFriends($u_id);

?>

<div class='col-lg-3 col-md-4'>
  <div class='card mb-3'>
      <div class='card-header h4'>
          Suggested Friends
      </div>
      <ul class="list-group list-group-flush">
        <?php
          if(!empty($data['suggested_friends'])){
            /** Get User's Friends **/
            foreach ($data['suggested_friends'] as $key => $friend) {
              /** Get User's Friends Data **/
              $num_mutual_friends = $friend['mutual_friends'];
              $friend_userName = CurrentUserData::getUserName($key);
              $friend_userImage = CurrentUserData::getUserImage($key);
              $online_check = CurrentUserData::getUserStatusDot($key);
              echo "<li class='list-group-item'>";
                echo "<a href='".SITE_URL."Profile/{$friend_userName}'>";
                  echo "<img src=".SITE_URL.IMG_DIR_PROFILE.$friend_userImage." class='rounded' style='height: 25px'>";
                echo "</a>&nbsp;";
                echo " $online_check <a href='".SITE_URL."Profile/{$friend_userName}'>";
                  echo "$friend_userName";
                echo "</a>";
                echo "<div class='float-right'> $num_mutual_friends Mutual Friends </div>";
              echo "</li>";
            }
          }else{
            echo "<li class='list-group-item'>You don't have any suggested friends. :(</li>";
            echo "<li class='list-group-item'><a href='".SITE_URL."Members'>Browse Site Members</a></li>";
          }
        ?>
      </ul>
  </div>
</div>
