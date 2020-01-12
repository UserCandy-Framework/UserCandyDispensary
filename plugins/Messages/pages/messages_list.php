<?php
/**
* Messages View Plugin Messages List
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.4
*/

use Helpers\{Csrf,Request,SuccessMessages,ErrorMessages,Url,Form,CurrentUserData};

$current_page = $get_var_2;

if($get_var_1 == 'Outbox'){
  /** Run data for Outbox **/
  if(isset($_POST['submit'])){

  // Check to make sure the csrf token is good
  if (Csrf::isTokenValid('messages')) {
    // Get Post Data
    $actions = Request::post('actions');
    $msg_id = Request::post('msg_id');

// Check to see if user is deleteing messages
if($actions == "delete"){
    // Delete selected messages from Outbox
  if(isset($msg_id)){
  foreach($msg_id as $del_msg_id){
      if($MessagesModel->deleteMessageOutbox($u_id, $del_msg_id)){
        // Success
    $m_del_success[] = true;
      }else{
        // Fail
    $m_del_error[] = true;
      }
  }
  if(count($m_del_success) >= 1){
    // Message Delete Success Display
    SuccessMessages::push('You Have Successfully Deleted Messages', 'Messages/Outbox/');
  }else if(count($m_del_error) >= 1){
    // Message Delete Error Display
    ErrorMessages::push('Messages Delete Failed', 'Messages/Outbox/');
  }

  }else{
  // Fail
  ErrorMessages::push('Nothing Was Selected to be Deleted', 'Messages/Outbox/');
  }
}
  }
}

// Collect Data for view
$data['title'] = "My Private Messages Outbox";
$data['welcomeMessage'] = "Welcome to your Private Messages Outbox";

  // Sets "to" username display
  $data['tofrom'] = " to ";
  $data['inboxoutbox'] = "outbox";

  // Get all message that are to current user
  $data['messages'] = $MessagesModel->getOutbox($u_id, $pages->getLimit($current_page, MESSAGE_PAGEINATOR_LIMIT));

  // Set total number of messages for paginator
  $total_num_messages = $MessagesModel->getTotalMessagesOutbox($u_id);
  $pages->setTotal($total_num_messages);
  // Send page links to view
  $pageFormat = SITE_URL."Messages/Outbox/"; // URL page where pages are
  $data['pageLinks'] = $pages->pageLinks($pageFormat, null, $current_page);

  // Message Quota Goods
  // Get total count of messages
  $data['quota_msg_ttl'] = $total_num_messages;
  $data['quota_msg_limit'] = MESSAGE_QUOTA_LIMIT;
  $data['quota_msg_percentage'] = $MessagesModel->getPercentage($data['quota_msg_ttl'], $data['quota_msg_limit']);

  // Check to see if user has reached message limit, if so show warning
  if($data['quota_msg_percentage'] >= "100"){
    $error[] = "<span class='fas fa-exclamation-sign' aria-hidden='true'></span>
                <b>Your Outbox is Full!</b>  You Can NOT send any messages!";
  }else if($data['quota_msg_percentage'] >= "90"){
    $error[] = "<span class='fas fa-exclamation-sign' aria-hidden='true'></span>
                <b>Warning!</b> Your Outbox is Almost Full!";
  }

  // What box are we showing
  $data['what_box'] = "Outbox";

  // Output errors if any
  if(isset($error)){ $data['error'] = $error; };

  // Setup Breadcrumbs
  $data['breadcrumbs'] = "<li class='breadcrumb-item'><a href='".SITE_URL."Messages'>Private Messages</a></li><li class='breadcrumb-item active'>".$data['title']."</li>";
  $data['csrfToken'] = Csrf::makeToken('messages');

  // Include Java Script for check all feature
  $js = "<script src='".Url::templatePath()."js/form_check_all.js'></script>";

}else{
  /** Run data for Inbox **/
  // Hidden Auto Check to make sure that messages that are marked
  // for delete by both TO and FROM users are removed from database
  $MessagesModel->cleanUpMessages();

  // Check to make sure user is trying to delete messages
  if(isset($_POST['submit'])){

    // Check to make sure the csrf token is good
    if (Csrf::isTokenValid('messages')) {
      // Get Post Data
      $actions = Request::post('actions');
      $msg_id = Request::post('msg_id');

      // Check to see if user is deleteing messages
      if($actions == "delete"){
        // Delete selected messages from Inbox
        if(isset($msg_id)){
          foreach($msg_id as $del_msg_id){
            if($MessagesModel->deleteMessageInbox($u_id, $del_msg_id)){
              // Success
              $m_del_success[] = true;
            }else{
              // Fail
              $m_del_error[] = true;
            }
          }
          if(count($m_del_success) >= 1){
            // Message Delete Success Display
            SuccessMessages::push('You Have Successfully Deleted Messages', 'Messages/Inbox/');
          }else if(count($m_del_error) >= 1){
            // Message Delete Error Display
            ErrorMessages::push('Messages Delete Failed', 'Messages/Inbox/');
          }
        }else{
          // Fail
          ErrorMessages::push('Nothing Was Selected to be Deleted', 'Messages/Inbox/');
        }
      }
      // Check to see if user is marking messages as read
      if($actions == "mark_read"){
        // Mark messages as read for all requested messages
        if(isset($msg_id)){
          foreach($msg_id as $del_msg_id){
            if($MessagesModel->markReadMessageInbox($u_id, $del_msg_id)){
              // Success
              $m_read_success[] = true;
            }else{
              // Fail
              $m_read_error[] = true;
            }
          }
          if(isset($m_read_success) && count($m_read_success) >= 1){
            // Message Delete Success Display
            SuccessMessages::push('You Have Successfully Marked Messages as Read', 'Messages/Inbox/');
          }else if(isset($m_read_error) && count($m_read_error) >= 1){
            // Message Delete Error Display
            ErrorMessages::push('Mark Messages Read Failed', 'Messages/Inbox/');
          }

        }else{
          // Fail
          ErrorMessages::push('Nothing Was Selected to be Marked as Read', 'Messages/Inbox/');
        }
      }
    }
  }

  // Collect Data for view
  $data['title'] = "My Private Messages Inbox";
  $data['welcomeMessage'] = "Welcome to Your Private Messages Inbox";

  // Sets "by" username display
  $data['tofrom'] = " by ";
  $data['inboxoutbox'] = "inbox";

  // Get all message that are to current user
  $data['messages'] = $MessagesModel->getInbox($u_id, $pages->getLimit($current_page, MESSAGE_PAGEINATOR_LIMIT));

  // Set total number of messages for paginator
  $total_num_messages = $MessagesModel->getTotalMessages($u_id);
  $pages->setTotal($total_num_messages);

  // Send page links to view
  $pageFormat = SITE_URL."Messages/Inbox/"; // URL page where pages are
  $data['pageLinks'] = $pages->pageLinks($pageFormat, null, $current_page);

  // Message Quota Goods
  // Get total count of messages
  $data['quota_msg_ttl'] = $total_num_messages;
  $data['quota_msg_limit'] = MESSAGE_QUOTA_LIMIT;
  $data['quota_msg_percentage'] = $MessagesModel->getPercentage($data['quota_msg_ttl'], $data['quota_msg_limit']);

  // Check to see if user has reached message limit, if so show warning
  if($data['quota_msg_percentage'] >= "100"){
    $error = "<span class='fas fa-exclamation-sign' aria-hidden='true'></span>
                <b>Your Inbox is Full!</b>  Other Site Members Can NOT send you any messages!";
  }else if($data['quota_msg_percentage'] >= "90"){
    $error = "<span class='fas fa-exclamation-sign' aria-hidden='true'></span>
                <b>Warning!</b> Your Inbox is Almost Full!";
  }

  // Output errors if any
  if(isset($error)){ $data['error'] = $error; };

  // Let view know inbox is in use
  $data['inbox'] = "true";
  // What box are we showing
  $data['what_box'] = "Inbox";

  // Setup Breadcrumbs
  $data['breadcrumbs'] = "<li class='breadcrumb-item'><a href='".SITE_URL."Messages'>Private Messages</a></li><li class='breadcrumb-item active'>".$data['title']."</li>";
  $data['csrfToken'] = Csrf::makeToken('messages');

  // Include Java Script for check all feature
  $js = "<script src='".Url::templatePath()."js/form_check_all.js'></script>";

}

?>

<div class='col-lg-9 col-md-8'>


	<div class='card mb-3'>
		<div class='card-header h4'>
			<?php echo $data['title'] ?>
		</div>
		<div class='card-body'>
			<p><?php echo $data['welcomeMessage'] ?></p>
			<table class='table table-striped table-hover table-bordered responsive'>
				<tr>
					<th colspan='2'>Message</th>
          <th><div align='center'><INPUT type='checkbox' onchange='checkAll(this)' name='msg_id[]' /></div></th>
				</tr>
				<?php
					if(!empty($data['messages'])){
            $this_url = SITE_URL."Messages/${data['what_box']}/";
            echo Form::open(array('method' => 'post', 'action' => $this_url));
						foreach($data['messages'] as $row) {
							echo "<tr>";
              echo "<td align='center' valign='middle'>";
                //Check to see if message is new
                if($row->date_read == NULL){
                  // Unread
                  echo "<span class='fas fa-star' aria-hidden='true' style='font-size:25px; color:#419641'></span>";
                }else{
                  // Read
                  echo "<span class='far fa-star' aria-hidden='true' style='font-size:25px; color:#CCC'></span>";
                }
              echo "</td>";
              echo "<td><a href='".SITE_URL."Messages/View/$row->id'><b>Subject:</b> $row->subject</a><br>";
							echo $data['tofrom'];
              if($data['inboxoutbox'] == 'inbox'){
                echo CurrentUserData::getUserStatusDot($row->from_userID);
              }else if($data['inboxoutbox'] == 'outbox'){
                echo CurrentUserData::getUserStatusDot($row->to_userID);
              }
              echo " <a href='".SITE_URL."Profile/$row->username'>$row->username</a>";
							echo " &raquo; ";
							echo  date("F d, Y - g:i A",strtotime($row->date_sent));
              echo "</td>";
              echo "<td>";
              echo Form::input(array('type' => 'checkbox', 'name' => 'msg_id[]', 'class' => 'form-control', 'value' => $row->id));
              echo "</td>";
							echo "</tr>";
						}
            echo "<input type='hidden' name='token_messages' value='".$data['csrfToken']."' />";
            echo "</tr><td colspan='3'>";
            echo "<div class='col-lg-7 col-md-7 col-sm-7 float-left' style='font-size:12px;'>";
              // Display Quta Info
              echo "<b>Total ${data['what_box']} Messages:</b> ${data['quota_msg_ttl']} - <b>Limit:</b> ${data['quota_msg_limit']}";
              // Check to see how full the inbox or outbox is and set color of progress bar
              if($data['quota_msg_percentage'] >= '90'){
                $set_prog_style = "progress-bar-danger";
              }else if($data['quota_msg_percentage'] >= '80'){
                $set_prog_style = "progress-bar-warning";
              }else if($data['quota_msg_percentage'] >= '70'){
                $set_prog_style = "progress-bar-info";
              }else{
                $set_prog_style = "progress-bar-success";
              }
              echo "<div class='progress'>
                      <div class='progress-bar $set_prog_style' role='progressbar' aria-valuenow='${data['quota_msg_percentage']}' aria-valuemin='0' aria-valuemax='100' style='min-width: 2em; width:${data['quota_msg_percentage']}%'>
                        ${data['quota_msg_percentage']}&#37;
                      </div>
                    </div>";
            echo "</div>";
            echo "<div class='col-lg-5 col-md-5 col-sm-5 input-group float-right'>";
              echo "<div class='input-group-prepend'>";
                echo "<span class='input-group-text'>Actions</span>";
              echo "</div>";
              echo "<select class='form-control' id='actions' name='actions'>";
                echo "<option>Select Action</option>";
                // Check to see if using inbox - oubox mark as read is disabled
                if($data['inbox'] == "true"){
                  echo "<option value='mark_read'>Make as Read</option>";
                }
                echo "<option value='delete'>Delete</option>";
              echo "</select>";
              echo "<span class='input-group-append'><button class='btn btn-success' name='submit' type='submit'>GO</button></span>";
            echo "</div>";
            echo "</td></tr>";
            // Check to see if there is more than one page
            if($data['pageLinks'] > "1"){
              echo "<tr><td colspan='3' align='center'>";
              echo $data['pageLinks'];
              echo "</td></tr>";
            }
            echo Form::close();
					}else{
            echo "<tr><td>No Messages to Display</td></tr>";
          }
				?>
			</table>
		</div>
	</div>
</div>
