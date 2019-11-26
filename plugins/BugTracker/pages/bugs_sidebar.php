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
      BugTracker
    </div>
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><a href="<?php echo SITE_URL; ?>BugTracker">BugTracker List</a></li>
      <li class="list-group-item"><a href="<?php echo SITE_URL; ?>BugTracker/Submit">Submit to BugTracker</a></li>
    </ul>
  </div>

  <div class='card mb-3'>
    <div class='card-header h4'>
      BugTracker Status
    </div>
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><a class="btn btn-sm btn-success" href="<?php echo SITE_URL; ?>BugTracker/1/New">
        <span class="badge badge-light"><?php echo $BugTrackerModel->getBugsStatusCount('New'); ?></span> New</a>
      </li>
      <li class="list-group-item"><a class="btn btn-sm btn-info" href="<?php echo SITE_URL; ?>BugTracker/1/Open">
        <span class="badge badge-light"><?php echo $BugTrackerModel->getBugsStatusCount('Open'); ?></span> Open</a>
      </li>
      <li class="list-group-item"><a class="btn btn-sm btn-primary" href="<?php echo SITE_URL; ?>BugTracker/1/InProgress">
        <span class="badge badge-light"><?php echo $BugTrackerModel->getBugsStatusCount('InProgress'); ?></span> InProgress</a>
      </li>
      <li class="list-group-item"><a class="btn btn-sm btn-warning" href="<?php echo SITE_URL; ?>BugTracker/1/Stalled">
        <span class="badge badge-light"><?php echo $BugTrackerModel->getBugsStatusCount('Stalled'); ?></span> Stalled</a>
      </li>
      <li class="list-group-item"><a class="btn btn-sm btn-secondary" href="<?php echo SITE_URL; ?>BugTracker/1/Resolved">
        <span class="badge badge-light"><?php echo $BugTrackerModel->getBugsStatusCount('Resolved'); ?></span> Resolved</a>
      </li>
      <li class="list-group-item"><a class="btn btn-sm btn-danger" href="<?php echo SITE_URL; ?>BugTracker/1/Closed">
        <span class="badge badge-light"><?php echo $BugTrackerModel->getBugsStatusCount('Closed'); ?></span> Closed</a>
      </li>
    </ul>
  </div>
</div>
