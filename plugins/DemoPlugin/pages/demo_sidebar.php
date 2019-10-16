<?php
/**
* Demo Plugin Sidebar
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version 1.0.0
*/

use Helpers\CurrentUserData;

?>

<div class='col-lg-3 col-md-4'>
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
