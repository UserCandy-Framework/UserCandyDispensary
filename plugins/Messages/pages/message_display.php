<?php
/**
* Messages View Plugin Messages Display
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.3
*/

use Helpers\{Csrf,Request,SuccessMessages,ErrorMessages,Url,Form,CurrentUserData, BBCode};

$m_id = $get_var_2;

// Check to make sure user is trying to send new message
if(isset($_POST['submit'])){
  // Check to make sure the csrf token is good
  if (Csrf::isTokenValid('messages')) {
    $msg_id = Request::post('message_id');
    if(isset($msg_id)){
      if($MessagesModel->deleteMessageInbox($u_id, $msg_id)){
        // Success
        $m_del_success_inbox = true;
      }else{
        if($MessagesModel->deleteMessageOutbox($u_id, $msg_id)){
          // Success
          $m_del_success_outbox = true;
        }else{
          // Fail
          $m_del_error = true;
        }
      }
    }
    if($m_del_success_inbox == true){
      // Message Delete Success Display
      SuccessMessages::push('You Have Successfully Deleted Message', 'Messages/Inbox/');
    }else if($m_del_success_outbox == true){
      // Message Delete Success Display
      SuccessMessages::push('You Have Successfully Deleted Message', 'Messages/Outbox/');
    }else if($m_del_error == true){
      // Message Delete Error Display
      ErrorMessages::push('Message Delete Failed', 'Messages');
    }
  }
}else{

  // Check to see if requested message exists and user is related to it
  if($MessagesModel->checkMessagePerm($u_id, $m_id)){
    // Message exist and user is related
    // Collect Data for view
    $data['title'] = "My Private Message";
    $data['welcomeMessage'] = "Welcome to Your Private Message";

    // Get requested message data
    $data['message'] = $MessagesModel->getMessage($m_id, $u_id);
  }else{
    // User Does not own message or it does not exist
    $data['title'] = "My Private Message - Error!";
    $data['welcomeMessage'] = "The requested private message does not exist!";
    $data['msg_error'] = "true";
  }
  // Setup Breadcrumbs
  $data['breadcrumbs'] = "<li class='breadcrumb-item'><a href='".SITE_URL."Messages'>Private Messages</a></li><li class='breadcrumb-item active'>".$data['title']."</li>";
  $data['csrfToken'] = Csrf::makeToken('messages');

}

if(empty($data['msg_error'])){ $data['msg_error'] = ""; }
if($data['msg_error'] == 'true'){$panelclass = "card-danger";}else{$panelclass = "card-secondary";}

?>

<div class='col-lg-9 col-md-8'>


	<div class='card <?php echo $panelclass; ?>'>
		<div class='card-header h4'>
			<?php echo $data['title'] ?>
		</div>
		<div class='card-body'>
			<p><?php echo $data['welcomeMessage'] ?></p>
				<?php
					if(isset($data['message'])){
            echo "<table class='table table-bordered table-striped responsive'>";
						foreach($data['message'] as $row) {
							echo "<tr>";
              echo "<td>$row->subject</td>";
              echo "</tr><tr><td>";
              echo "<b>Date Sent:</b> ".date("F d, Y - g:i A",strtotime($row->date_sent))."<br>";
              // Check to see if message is marked as read yet
              if(isset($row->date_read)){
                echo "<b>Date Read:</b> ".date("F d, Y - g:i A",strtotime($row->date_read))."<br>";
              }
							echo "<b>From:</b> <a href='".SITE_URL."Profile/$row->username'>$row->username</a>";
              echo "</td></tr><tr>";
              $content_output = BBCode::getHtml($row->content);
							echo "<td class='forum'>$content_output</td>";
							echo "</tr><tr><td>";
                echo Form::open(array('method' => 'post', 'action' => SITE_URL.'Messages/New/', 'style' => 'display:inline'));
                  echo "<input type='hidden' name='token_messages' value='{$data['csrfToken']}' />";
                  echo "<input type='hidden' name='reply' value='true' />";
                  echo "<input type='hidden' name='to_username' value='$row->username' />";
                  echo "<input type='hidden' name='subject' value=\"$row->subject\" />";
                  echo "<input type='hidden' name='content' value=\"$row->content\" />";
                  echo "<input type='hidden' name='date_sent' value='".date("F d, Y - g:i A",strtotime($row->date_sent))."' />";
                  echo "<button class='btn btn-md btn-success' name='submit' type='submit'>";
                    echo "Reply";
                  echo "</button>";
                echo Form::close();
                echo "<a href='#DeleteModal' class='btn btn-sm btn-danger trigger-btn float-right' data-toggle='modal'>Delete Message</a>";
              echo "</td></tr>";
						}
            echo "</table>";
					}
				?>
		</div>
	</div>
</div>

  <div class='modal fade' id='DeleteModal' tabindex='-1' role='dialog' aria-labelledby='DeleteLabel' aria-hidden='true'>
    <div class='modal-dialog' role='document'>
      <div class='modal-content'>
        <div class='modal-header'>
          <h5 class='modal-title' id='DeleteLabel'>Message Delete</h5>
          <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
          </button>
        </div>
        <div class='modal-body'>
          Do you want to delete this message?
        </div>
        <div class='modal-footer'>
          <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
          <?php
            echo Form::open(array('method' => 'post', 'action' => '', 'style' => 'display:inline'));
              echo "<input type='hidden' name='token_messages' value='{$data['csrfToken']}' />";
              echo "<input type='hidden' name='delete' value='true' />";
              echo "<input type='hidden' name='message_id' value='$row->id' />";
              echo "<button class='btn btn-md btn-danger' name='submit' type='submit'>";
                echo "Delete";
              echo "</button>";
            echo Form::close();
          ?>
        </div>
      </div>
    </div>
  </div>
