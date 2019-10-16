<?php
/**
* UserCandy Wall Friends Sidebar
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version 4.3.0
*/

use Core\Language;
use Helpers\CurrentUserData;

?>
<script>
function processFriends()
  {
  var url='<?=SITE_URL?>Friends/UN-ASC/1/' + document.getElementById('friendSearch').value;
  location.href=url;
  return false;
  }
</script>

<div class='col-lg-3 col-md-4'>
  <div class='card mb-3'>
    <div class='card-header h4'>
      My Friends
    </div>
    <ul class='list-group list-group-flush'>
      <li class='list-group-item'><span class='fas fa-inbox'></span> <a href='<?php echo SITE_URL ?>FriendRequests' rel='nofollow'>Friend Requests
        <?php
            /** Check to see if there are any pending friend requests **/
            $new_friend_count = CurrentUserData::getFriendRequests($currentUserData[0]->userID);
            if($new_friend_count >= "1"){
                echo " <span class='badge badge-primary'>".$new_friend_count." New</span>";
            }
        ?>
      </a></li>
      <li class='list-group-item'><span class='fas fa-user'></span> <a href='<?php echo SITE_URL ?>Friends' rel='nofollow'>Friends
        <?php
            /** Check to see how many friends user has **/
            $total_friend_count = CurrentUserData::getFriendsCount($currentUserData[0]->userID);
            if($total_friend_count >= "1"){
              echo " <span class='badge badge-primary'>".$total_friend_count." Total</span>";
            }
        ?>
      </a></li>
    </ul>
  </div>

  <div class='card mb-3'>
      <form onSubmit="return processFriends();" class="form" method="post">
          <div class='card-header h4'>
          <?=Language::show('search_friends', 'Friends'); ?>
          </div>
          <div class='card-body'>
              <div class="form-group">
              <input type="friendSearch" class="form-control" id="friendSearch" placeholder="<?=Language::show('search_friends', 'Friends'); ?>" value="<?php if(isset($data['search'])){ echo $data['search']; } ?>">
              </div>
          <button type="submit" class="btn btn-secondary"><?=Language::show('search', 'Friends'); ?></button>
          </div>
      </form>
  </div>

    <div class='card mb-3'>
        <div class='card-header h4'>
          Friends List
        </div>
        <ul class="list-group list-group-flush">
          <?php
            if(!empty($friends)){
              /** Get User's Friends **/
              foreach ($friends as $friend) {
                /** Get User's Friends Data **/
                $friend_userName = CurrentUserData::getUserName($friend->userID);
                $friend_userImage = CurrentUserData::getUserImage($friend->userID);
                $online_check = CurrentUserData::getUserStatusDot($friend->userID);
                echo "<li class='list-group-item'>";
                  echo "<a href='".SITE_URL."Profile/{$friend_userName}'>";
                    echo "<img src=".SITE_URL.IMG_DIR_PROFILE.$friend_userImage." class='rounded' style='height: 25px'>";
                  echo "</a>&nbsp;";
                  echo " $online_check <a href='".SITE_URL."Profile/{$friend_userName}'>";
                    echo "$friend_userName";
                  echo "</a>";
                echo "</li>";
              }
            }else{
              echo "<li class='list-group-item'>You don't have any friends. :(</li>";
              echo "<li class='list-group-item'><a href='".SITE_URL."Members'>Browse Site Members</a></li>";
            }
          ?>
        </ul>
    </div>

    <div class='card mb-3'>
        <div class='card-header h4'>
            Suggested Friends
        </div>
        <ul class="list-group list-group-flush">
          <?php
            if(!empty($suggested_friends)){
              /** Get User's Friends **/
              foreach ($suggested_friends as $key => $friend) {
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
