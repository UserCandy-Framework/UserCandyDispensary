<?php
/**
* Messages View Plugin Messages New
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version 1.0.0
*/

use Helpers\{Csrf,Request,SuccessMessages,ErrorMessages,Url,Form,CurrentUserData,BBCode};

$to_user = $get_var_2;
$subject = $get_var_3;

// Check to see if user is over quota
// Disable New Message Form is they are
if($MessagesModel->checkMessageQuota($u_id)){
// user is over limit, disable new message form
$data['hide_form'] = "true";
    $error[] = "<span class='fas fa-exclamation-sign' aria-hidden='true'></span>
              <b>Your Outbox is Full!</b>  You Can NOT send any messages!";
}

// Check to make sure user is trying to send new message
if(isset($_POST['submit'])){
// Check to make sure the csrf token is good
if (Csrf::isTokenValid('messages')) {
// Get data from post
(Request::post('to_username') !== null) ? $to_username = Request::post('to_username') : $to_username = "";
(Request::post('subject') !== null) ? $subject = Request::post('subject') : $subject = "";
(Request::post('content') !== null) ? $content = Request::post('content') : $content = "";
(Request::post('reply') !== null) ? $reply = Request::post('reply') : $reply = "";

        // Check to see if this is coming from a reply button
        if($reply != "true"){
            // Check to make sure user completed all required fields in form
            if(empty($to_username)){
                // Username field is empty
                $error[] = 'Username Field is Blank!';
            }
            if(empty($subject)){
                // Subject field is empty
                $error[] = 'Subject Field is Blank!';
            }
            if(empty($content)){
                // Username field is empty
                $error[] = 'Message Content Field is Blank!';
            }
            // Check for errors before sending message
            if(!isset($error)){
                // Get the userID of to username
                $to_userID = $MessagesModel->getUserIDFromUsername($to_username);
                // Check to make sure user exists in Database
                if(isset($to_userID)){
                    // Check to see if to user's inbox is not full
                    if($MessagesModel->checkMessageQuotaToUser($to_userID)){
                  // Run the Activation script
                        if($MessagesModel->sendmessage($to_userID, $u_id, $subject, $content)){
                            // Success
                            SuccessMessages::push('You Have Successfully Sent a Private Message', 'Messages');
                            $data['hide_form'] = "true";
                    }else{
                            // Fail
                            $error[] = 'Message Send Failed';
                        }
                    }else{
                        // To user's inbox is full.  Let sender know message was not sent
                        $error[] = '<b>{$to_username}&#39;s Inbox is Full!</b>  Sorry, Message was NOT sent!';
                    }
                }else{
                    // User does not exist
                    $error[] = 'Message Send Failed - To User Does Not Exist';
                }
            }// End Form Complete Check
        }else{
            // Get data from reply POST
            (Request::post('to_username') !== null) ? $to_user = Request::post('to_username') : $to_user = "";
            (Request::post('subject') !== null) ? $subject = Request::post('subject') : $subject = "";
            (Request::post('content') !== null) ? $content = Request::post('content') : $content = "";
            (Request::post('date_sent') !== null) ? $date_sent = Request::post('date_sent') : $date_sent = "";
            // Add Reply details to subject ex: RE:
            $data['subject'] = "RE: ".$subject;
            // Clean up content so it looks pretty
            $content_reply = "&#10;&#10;&#10;&#10; ##############################";
            $content_reply .= "&#10; # PREVIOUS MESSAGE";
            $content_reply .= "&#10; # From: $to_username";
            $content_reply .= "&#10; # Sent: $date_sent ";
            $content_reply .= "&#10; ############################## &#10;&#10;";
            $content_reply .= $content;
            $data['content'] = $content_reply;
        }// End Reply Check
}
}

// Check to see if there were any errors, if so then auto load form data
if(isset($error) && !isset($data['subject']) && !isset($data['content'])){
    // Auto Fill form to make things eaiser for user
    $data['subject'] = Request::post('subject');
    $data['content'] = Request::post('content');
    // Output errors if any
    if(!empty($error)){ $data['error'] = $error; };
}
if(!isset($data['subject'])){ $data['subject'] = $subject; }
if(!isset($data['to_username'])){ $data['to_username'] = $to_user; }

// Collect Data for view
$data['title'] = "My Private Message";
$data['welcomeMessage'] = "Welcome to Your Private Message Creator";
$data['csrfToken'] = Csrf::makeToken('messages');

// Setup Breadcrumbs
$data['breadcrumbs'] = "<li class='breadcrumb-item'><a href='".SITE_URL."Messages'>Private Messages</a></li><li class='breadcrumb-item active'>".$data['title']."</li>";


?>

<div class='col-lg-9 col-md-8'>

	<div class='card mb-3'>
		<div class='card-header h4'>
			<?php echo $data['title'] ?>
		</div>
		<div class='card-body'>
			<p><?php echo $data['welcomeMessage'] ?></p>

<?php
    // Check to see if message form is disabled
    if(empty($data['hide_form'])){ $data['hide_form'] = ""; }
    if($data['hide_form'] != "true"){
        if(empty($data['to_username'])){ $data['to_username'] = ""; }
        if(empty($data['subject'])){ $data['subject'] = ""; }
        if(empty($data['content'])){ $data['content'] = ""; }
?>

			<?php echo Form::open(array('method' => 'post')); ?>

      <!-- To UserName -->
      <div class='input-group mb-3' style='margin-bottom: 25px'>
		<div class='input-group-prepend'>
			<span class='input-group-text'><i class='fas fa-user'></i> </span>
		</div>
        <?php echo Form::input(array('type' => 'text', 'name' => 'to_username', 'class' => 'form-control', 'value' => $data['to_username'], 'placeholder' => 'To Username', 'maxlength' => '100')); ?>
      </div>

      <!-- Subject -->
      <div class='input-group mb-3' style='margin-bottom: 25px'>
		<div class='input-group-prepend'>
			<span class='input-group-text'><i class='fas fa-book'></i> </span>
		</div>
        <?php echo Form::input(array('type' => 'text', 'name' => 'subject', 'class' => 'form-control', 'value' => urldecode($data['subject']), 'placeholder' => 'Subject', 'maxlength' => '100')); ?>
      </div>

      <!-- BBCode Buttons -->
      <?=BBCode::displayButtons('content')?>

      <!-- Message Content -->
      <div class='input-group mb-3' style='margin-bottom: 25px'>
		<div class='input-group-prepend'>
			<span class='input-group-text'><i class='fas fa-pencil-alt'></i> </span>
		</div>
        <?php echo Form::textBox(array('type' => 'text', 'name' => 'content', 'id' => 'content', 'class' => 'form-control', 'value' => $data['content'], 'placeholder' => 'Message Content', 'rows' => '6')); ?>
      </div>

        <!-- CSRF Token -->
        <input type="hidden" name="token_messages" value="<?php echo $data['csrfToken']; ?>" />
        <button class="btn btn-md btn-success" name="submit" type="submit">
          <?php // echo Language::show('update_profile', 'Auth'); ?>
          Send Message
        </button>
      <?php echo Form::close(); ?>

<?php
  // END Check to see if message form is disabled
  }
?>

		</div>
	</div>
</div>
