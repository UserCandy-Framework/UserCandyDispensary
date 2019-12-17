<?php
/**
* Friends Plugin Friends Sidebar
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.3
*/

use Core\Language;
use Helpers\CurrentUserData;

?>

<script>
function process()
  {
  var url="<?php echo SITE_URL; ?>Friends/UN-ASC/1/" + document.getElementById("friendsSearch").value;
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
      <li class='list-group-item'><span class='fas fa-inbox'></span> <a href='<?php echo SITE_URL ?>Friends/Requests/' rel='nofollow'>Friend Requests
        <?php
            /** Check to see if there are any pending friend requests **/
            $new_friend_count = CurrentUserData::getFriendRequests($currentUserData[0]->userID);
            if($new_friend_count >= "1"){
                echo " <span class='badge badge-primary'>".$new_friend_count." New</span>";
            }
        ?>
      </a></li>
      <li class='list-group-item'><span class='fas fa-user'></span> <a href='<?php echo SITE_URL ?>Friends/' rel='nofollow'>Friends
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
        <form onSubmit="return process();" class="form" method="post">
            <div class='card-header h4'>
            <?=Language::show('search_friends', 'Friends'); ?>
            </div>
            <div class='card-body'>
                <div class="form-group">
                <input type="friendsSearch" class="form-control" id="friendsSearch" placeholder="<?=Language::show('search_friends', 'Friends'); ?>" value="<?php if(isset($data['search'])){ echo $data['search']; } ?>">
                </div>
            <button type="submit" class="btn btn-secondary"><?=Language::show('search', 'Friends'); ?></button>
            </div>
        </form>
    </div>

    <div class='card mb-3'>
        <div class='card-header h4'>
            Members Status
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><a href="<?php echo SITE_URL; ?>Members">Members: <?php echo CurrentUserData::getMembers(); ?></a></li>
            <li class="list-group-item"><a href="<?php echo SITE_URL; ?>Online-Members">Members Online: <?php echo CurrentUserData::getOnlineMembers(); ?></a></li>
        </ul>
    </div>
</div>
