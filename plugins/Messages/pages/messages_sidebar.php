<?php
/**
* Messages View Plugin Message Sidebar
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.4
*/

// Check for new messages in inbox
$data['new_messages_inbox'] = $MessagesModel->getUnreadMessages($u_id);

?>

<div class='col-lg-3 col-md-4'>
  <div class='card mb-3'>
    <div class='card-header h4'>
      Private Messages
    </div>
    <ul class='list-group list-group-flush'>
      <li class='list-group-item'><span class='fas fa-inbox'></span> <a href='<?=SITE_URL ?>Messages/Inbox/' rel='nofollow'>Inbox
        <?php
          // Check to see if there are any unread messages in inbox
          if($data['new_messages_inbox'] >= "1"){
            echo " <span class='badge badge-primary'>{$data['new_messages_inbox']} New</span>";
          }
        ?>
      </a></li>
      <li class='list-group-item'><span class='fas fa-road'></span> <a href='<?=SITE_URL ?>Messages/Outbox/' rel='nofollow'>Outbox</a></li>
      <li class='list-group-item'><span class='fas fa-pencil-alt'></span> <a href='<?=SITE_URL ?>Messages/New/' rel='nofollow'>Create New Message</a></li>
    </ul>
  </div>
</div>
