<?php
/**
* UserApplePie v4 Forum View Plugin Topic
*
* UserApplePie - Forum Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.2 for UAP v.4.3.0
*/

use Helpers\{ErrorMessages,Csrf,Request,SimpleImage,SuccessMessages,Url,CurrentUserData,TimeDiff,BBCode,Form};

/** Forum Topic View **/

/** Get Data from URL **/
(empty($get_var_2)) ? $id = null : $id = $get_var_2;
(empty($get_var_3)) ? $current_page = "1" : $current_page = $get_var_3;

/** Check to see if topic is requested by string or int **/
if(!ctype_digit($id)){
  $id = $model->get_topic_url_id($id);
}

/** Check to see if user entered a Topic ID **/
if(!$id){
  ErrorMessages::push('The Forum Topic entered does not exist!', 'Forum');
}

// Get Cat ID for this topic
$topic_forum_id = $model->forum_topic_cat_id($id);

// Get Requested Topic's Title and Description
$data['forum_cat'] = $model->forum_cat($topic_forum_id);
$data['forum_cat_des'] = $model->forum_cat_des($topic_forum_id);
$data['forum_topics'] = $model->forum_topics($topic_forum_id);

// Get Requested Topic Information
$data['topic_id'] = $id;
$data['title'] = $model->topic_title($id);
$data['topic_creator'] = $model->topic_creator($id);
$data['topic_date'] = $model->topic_date($id);
$data['topic_content'] = $model->topic_content($id);
$data['site_description'] = substr($data['topic_content'], 0, 255);
$data['topic_edit_date'] = $model->topic_edit_date($id);
$data['topic_status'] = $model->topic_status($id);
$data['topic_allow'] = $model->topic_allow($id);

// Get hidden information if there is any
$data['hidden_userID'] = $model->topic_hidden_userID($id);
$data['hidden_reason'] = $model->topic_hidden_reason($id);
$data['hidden_timestamp'] = $model->topic_hidden_timestamp($id);

// Check to see if current user owns the origianal post
$data['current_userID'] = $u_id;
$data['topic_userID'] = $model->topic_userID($id);

// Get current page number
if($current_page > 1){
    $data['current_page'] = $current_page;
}else{
    $data['current_page'] = "1";
}

// Get set ammount of replies per page
$data['topic_reply_limit'] = $forum_topic_reply_limit;

// Check to see if current user is admin
$data['is_admin'] = $auth->checkIsAdmin($u_id);

// Check to see if current user is moderator
$data['is_mod'] = $auth->checkIsMod($u_id);

// Check to see if current user is a new user
$data['is_new_user'] = $auth->checkIsNewUser($u_id);

// Get replys that are related to Requested Topic
$data['topic_replys'] = $model->forum_topic_replys($id, $pagesReply->getLimit($current_page, $forum_topic_reply_limit));

// Check to see if user has posted on this topic
$data['checkUserPosted'] = $model->checkUserPosted($id, $u_id);

// If user has not yet posted, then we set subcribe to true for new posts
if($data['checkUserPosted'] == true){
    // Check to see if current user is subscribed to this topic
    $data['is_user_subscribed'] = $model->checkTopicSubscribe($id, $u_id);
}else{
    $data['is_user_subscribed'] = true;
}

// Track user activity for current topic
$data['update_tracking'] = $model->forum_tracker($u_id,$id,$topic_forum_id);

// Set total number of messages for paginator
$total_num_replys = $model->getTotalReplys($id);
$pagesReply->setTotal($total_num_replys);

// Send page links to view
$pageFormat = SITE_URL."Topic/$id/"; // URL page where pages are
$data['pageLinks'] = $pagesReply->pageLinks($pageFormat, null, $current_page);

// Get related images if any
$data['forum_topic_images'] = $model->getForumImagesTopic($id);

// Get Action from POST
(isset($_POST['action'])) ? $data['action'] = Request::post('action') : $data['action'] = "";
(isset($_POST['edit_reply_id'])) ? $data['edit_reply_id'] = Request::post('edit_reply_id') : $data['edit_reply_id'] = "";

/** Check to see if user has any unpublished replies for this topic **/
$data['user_reply_id'] = $model->getUnPublishedWorkReply($u_id, $id);
$data['fpr_content'] = $model->getReplyContent($data['user_reply_id']);

// Check for topic reply autosave
if(isset($_POST['forum_topic_reply_autosave'])){
  if($_POST['forum_topic_reply_autosave'] == "autosave_topic_reply"){
    /** Forum Auto Save **/
    // Check to make sure the csrf token is good
    if (Csrf::isTokenValid('forum')) {
      /** Token Good **/
      $data['fpr_content'] = Request::post('fpr_content');
      $data['fpr_post_id'] = Request::post('fpr_post_id');
      if(!empty($data['fpr_post_id'])){
        $update_topic = $model->updateSavedTopicReply($data['fpr_post_id'], $data['fpr_content'], $u_id);
        echo $data['fpr_post_id'];
      }else{
        /** New Forum Post - Create new post **/
        $new_topic = $model->sendTopicReply($u_id, $id, $topic_forum_id, $data['fpr_content'], $data['is_user_subscribed'], "0");
        echo $new_topic;
      }
    }
  }
}else{

  // Check to see if user is submitting a new topic reply
  if(isset($_POST['submit'])){

    // Check to make sure the csrf token is good
    if (Csrf::isTokenValid('forum')) {

      // Check to see if user is editing topic
      if($data['action'] == "update_topic"){
        // Get data from post
        $data['forum_content'] = Request::post('forum_content');
        $data['forum_title'] = strip_tags(Request::post('forum_title'));
          // Check to make sure user completed all required fields in form
          if(empty($data['forum_title'])){
            // Subject field is empty
            $error[] = 'Topic Title Field is Blank!';
          }
          if(empty($data['forum_content'])){
            // Subject field is empty
            $error[] = 'Topic Content Field is Blank!';
          }
          // Check to make sure user owns the content they are trying to edit
          // Get the id of the user that owns the post that is getting edited
          if($u_id != $model->getTopicOwner($id)){
            // User does not own this content
            $error[] = 'You Do Not Own The Content You Were Trying To Edit!';
          }
          // Check for errors before sending message
          if(!isset($error)){
              // No Errors, lets submit the new topic to db
              if($model->updateTopic($id, $data['forum_title'], $data['forum_content'])){
                // Success
                SuccessMessages::push('You Have Successfully Updated a Topic', 'Forum/Topic/'.$id);
              }else{
                // Fail
                $error[] = 'Edit Topic Failed';
              }
          }// End Form Complete Check
      }
      // Check to see if user is editing or creating topic reply
      else if($data['action'] == "update_reply"){
        // Get data from post
        $data['fpr_content'] = Request::post('fpr_content');
          // Check to make sure user completed all required fields in form
          if(empty($data['fpr_content'])){
            // Subject field is empty
            $error[] = 'Topic Reply Content Field is Blank!';
          }
          // Check to make sure user owns the content they are trying to edit
          // Get the id of the user that owns the post that is getting edited
          if($u_id != $model->getReplyOwner($data['edit_reply_id'])){
            // User does not own this content
            $error[] = 'You Do Not Own The Content You Were Trying To Edit!';
          }
          // Check for errors before sending message
          if(!isset($error)){
              // No Errors, lets submit the new topic to db
              if($model->updateTopicReply($data['edit_reply_id'], $data['fpr_content'], $data['current_userID'])){
                // Success
                SuccessMessages::push('You Have Successfully Updated a Topic Reply', 'Forum/Topic/'.$id.'/'.$redirect_page_num.'#topicreply'.$data['edit_reply_id']);
              }else{
                // Fail
                $error[] = 'Edit Topic Reply Failed';
              }
          }// End Form Complete Check
      }else if($data['action'] == "new_reply"){
        // Get data from post
        $afpr_post_id = strip_tags(Request::post('fpr_post_id'));
        $data['fpr_content'] = Request::post('fpr_content');
          // Check to make sure user completed all required fields in form
          if(empty($data['fpr_content'])){
            // Subject field is empty
            $error[] = 'Topic Reply Content Field is Blank!';
          }
          // Check for errors before sending message
          if(!isset($error)){
              // No Errors, lets submit the new topic to db
              if($model->sendTopicReply($u_id, $id, $topic_forum_id, $data['fpr_content'], $data['is_user_subscribed'], "1", $afpr_post_id)){
                // Get Submitted Reply ID
                $reply_id = $model->lastTopicReplyID($id);
                // Check to see if post is going on a new page
                $page_reply_limit = $forum_topic_reply_limit;
                $redirect_page_num = ceil(($total_num_replys + 1) / $page_reply_limit);

                /** Ready site to upload Files for TOPIC REPLY **/
                if(!empty($_FILES['forumImage']['name'][0])){
                  $countfiles = count($_FILES['forumImage']['name']);
                  for($i=0;$i<$countfiles;$i++){
                    // Check to see if an image is being uploaded
                    if(!empty($_FILES['forumImage']['tmp_name'][$i])){
                        $picture = file_exists($_FILES['forumImage']['tmp_name'][$i]) || is_uploaded_file($_FILES['forumImage']['tmp_name'][$i]) ? $_FILES ['forumImage']['tmp_name'][$i] : array ();
                        if($picture != ""){
                            // Get file size for db
                            $check = getimagesize ( $picture );
                            $img_dir_forum_reply = IMG_DIR_FORUM_REPLY;
                            // Check to make sure image is good
                            if($check['size'] < 5000000 && $check && ($check['mime'] == "image/jpeg" || $check['mime'] == "image/png" || $check['mime'] == "image/gif")){
                                // Check to see if Img Upload Directory Exists, if not create it
                                if(!file_exists(ROOTDIR.$img_dir_forum_reply)){
                                  mkdir(ROOTDIR.$img_dir_forum_reply,0777,true);
                                }
                                // Format new image and upload it to server
                                $image = new SimpleImage($picture);
                                $new_image_name = "forum-image-topic-reply-uid{$u_id}-fid{$id}-ftid{$reply_id}";
                                $rand_string = substr(str_shuffle(md5(time())), 0, 10);
                                $img_name = $new_image_name.'-'.$rand_string.'.gif';
                                $img_max_size = explode(',', $forum_max_image_size);
                                $image->best_fit($img_max_size[0],$img_max_size[1])->save(ROOTDIR.$img_dir_forum_reply.$img_name);
                                // Make sure image was Successfull
                                if($img_name){
                                  // Add new image to database
                                  if($model->sendNewImage($u_id, $img_name, $img_dir_forum_reply, $file_size, $topic_forum_id, $id, $reply_id)){
                                    $img_success = "<br> Image Successfully Uploaded";
                                  }else{
                                    $img_success = "<br> No Image Uploaded";
                                  }
                                }
                            }else{
                              $img_success = "<br> Image was NOT uploaded because the file size was too large!";
                            }
                        }else{
                            $img_success = "<br> No Image Selected to Be Uploaded";
                        }
                    }else{
                        $img_success = "<br> No Image Selected to Be Uploaded";
                    }
                  }
                }else{
                  $img_success = "<br> No Image Selected to Be Uploaded";
                }
                // Send emails to those who are subscribed to this topic
                $model->sendTopicSubscribeEmails($id, $u_id, $data['title'], $data['forum_cat'], $data['fpr_content']);

                // Success
                SuccessMessages::push('You Have Successfully Created a New Topic Reply'.$img_success, 'Forum/Topic/'.$id.'/'.$redirect_page_num.'#topicreply'.$reply_id);
                $data['hide_form'] = "true";
              }else{
                // Fail
                $error[] = 'New Topic Reply Create Failed';
              }
          }// End Form Complete Check
      }else if($data['action'] == "lock_topic" && ($data['is_admin'] == true || $data['is_mod'] == true)){
        // Update database with topic locked (2)
        if($model->updateTopicLockStatus($id, "2")){
          SuccessMessages::push('You Have Successfully Locked This Topic', 'Forum/Topic/'.$id);
        }
      }else if($data['action'] == "unlock_topic" && ($data['is_admin'] == true || $data['is_mod'] == true)){
        // Update the database with topic unlocked (1)
        if($model->updateTopicLockStatus($id, "1")){
          SuccessMessages::push('You Have Successfully UnLocked This Topic', 'Forum/Topic/'.$id);
        }
      }else if($data['action'] == "hide_topic" && ($data['is_admin'] == true || $data['is_mod'] == true)){
        // Update database with topic hidden (TRUE)
        $hide_reason = Request::post('hide_reason');
        if($model->updateTopicHideStatus($id, "FALSE", $u_id, $hide_reason)){
          SuccessMessages::push('You Have Successfully Hidden This Topic', 'Forum/Topic/'.$id);
        }
      }else if($data['action'] == "unhide_topic" && ($data['is_admin'] == true || $data['is_mod'] == true)){
        // Update the database with topic unhide (FALSE)
        if($model->updateTopicHideStatus($id, "TRUE", $u_id, "UnHide")){
          SuccessMessages::push('You Have Successfully UnHide This Topic', 'Forum/Topic/'.$id);
        }
      }else if($data['action'] == "hide_reply" && ($data['is_admin'] == true || $data['is_mod'] == true)){
        // Update database with topic reply hidden (TRUE)
        $hide_reason = Request::post('hide_reason');
        $reply_id = Request::post('reply_id');
        $reply_url = Request::post('reply_url');
        if($model->updateReplyHideStatus($reply_id, "FALSE", $u_id, $hide_reason)){
          SuccessMessages::push('You Have Successfully Hidden Topic Reply', $reply_url);
        }
      }else if($data['action'] == "unhide_reply" && ($data['is_admin'] == true || $data['is_mod'] == true)){
        // Update the database with topic reply unhide (FALSE)
        $reply_id = Request::post('reply_id');
        $reply_url = Request::post('reply_url');
        if($model->updateReplyHideStatus($reply_id, "TRUE", $u_id, "UnHide")){
          SuccessMessages::push('You Have Successfully UnHide Topic Reply', $reply_url);
        }
      }else if($data['action'] == "subscribe" && isset($u_id)){
        // Update users topic subcrition status as true
        if($model->updateTopicSubcrition($id, $u_id, "true")){
          SuccessMessages::push('You Have Successfully Subscribed to this Topic', 'Forum/Topic/'.$id);
        }
      }else if($data['action'] == "unsubscribe" && isset($u_id)){
        // Update users topic subcrition status as false
        if($model->updateTopicSubcrition($id, $u_id, "false")){
          SuccessMessages::push('You Have Successfully UnSubscribed from this Topic', 'Forum/Topic/'.$id);
        }
      }// End Action Check
    } // End token check
  } // End post check

  // Check to see if we are displaying an array of errors
  if(is_array($error)){
    // Not an array, display single error
    $error_msg = "";
    foreach($error as $em){
      $error_msg .= "<br>$em";
    }
  }else{
    $error_msg = $error;
  }

  // Output errors if any
  if(!empty($error)){ ErrorMessages::push('Error with Forum <Br>'.$error_msg, 'Forum/Topic/'.$id); };

  /** Check to see if PageViews helper is installed **/
  if($DispenserModel->checkDispenserEnabled('PageViews')){
    // Update and Get Views Data
    $data['PageViews'] = PageViews::views('true', $id, 'Forum_Topic', $u_id);
  }

  // Get Recent Posts List for Sidebar
  $data['forum_recent_posts'] = $model->forum_recent_posts();

  // Setup Breadcrumbs
  $data['breadcrumbs'] = "<li class='breadcrumb-item'><a href='".SITE_URL."Forum'>".$forum_title."</a></li><li class='breadcrumb-item'><a href='".SITE_URL."Forum/Topics/$topic_forum_id'>".$data['forum_cat']."</a><li class='breadcrumb-item active'>".$data['title']."</li>";

  // Ready the token!
  $data['csrf_token'] = Csrf::makeToken('forum');

  /* Add Java Stuffs */
  $js .= "<script src='".Url::customPath('plugins', 'Forum')."js/forum_autosave_topic_reply.js'></script>";

?>

<div class='col-lg-9 col-md-8'>

	<div class='card mb-3'>
		<div class='card-header h4'>
			<?php echo $data['title'] ?>
      <?php
          // Display Locked Message if Topic has been locked by admin
          if($data['topic_status'] == 2){
            echo " <strong><font color='red'>Topic Locked</font></strong> ";
          }
          // Display Disallowed message if topic has been blocked
          if($data['topic_allow'] == "FALSE"){
            $hidden_userName = CurrentUserData::getUserName($data['hidden_userID']);
            echo " <strong><font color='red'>Topic Disabled By Mod/Admin</font></strong> ";
            echo "<hr>";
            echo "<b>Topic Hidden By</b>: ".$hidden_userName." <b>@</b> ".$data['hidden_timestamp']."<br>";
            echo "<b>Reason</b>: ".$data['hidden_reason'];
          }
       ?>
		</div>
		<div class='card-body'>
			<?php
      // Check to see if topic is allowed
      if($data['topic_allow'] == "TRUE"){
        /** Check to see if PageViews helper is installed **/
        if($DispenserModel->checkDispenserEnabled('PageViews')){
          // Display Views Count
          echo "<div class='btn btn-sm btn-info'>Views <span class='badge badge-light'>".$data['PageViews']."</span></div>";
        }
        /** Check to see if Sweets helper is installed **/
        if($DispenserModel->checkDispenserEnabled('Sweets')){
          // Display Total Sweets Count for Topic and All Replys
          echo Sweets::getTotalSweets($data['topic_id'], 'Forum_Topic', 'Forum_Topic_Reply');
        }
        // Display total images
        echo "<div class='btn btn-success btn-sm'> Images <span class='badge badge-light'>";
          echo $model->getImageCountForum('Topic', $data['topic_id']);
        echo "</span></div>";
        echo "<hr>";

        // Topic Display
    		echo "<div class='card border-primary mb-3'>";
    			echo "<div class='card-header h4'>";
    				echo "<div class='row'>";
    					echo "<div class='col-lg-6 col-md-6 col-sm-6'>";
    						// Show user main pic
                // Get user name from userID
                $f_p_user_name = CurrentUserData::getUserName($data['topic_creator']);
                echo "<meta name='author' content='$f_p_user_name'>";
                echo "<meta name='date' content='".date("Y-m-d", strtotime($data['topic_date']))."' scheme='YYYY-MM-DD'>";
    						echo " <a href='".SITE_URL."Profile/$f_p_user_name' rel='author'>$f_p_user_name</a> ";
    					echo "</div>";
    					echo "<div class='col-lg-6 col-md-6 col-sm-6' style='text-align:right'>";
    						// Display how long ago this was posted
                $data_topic_date = $data['topic_date'];
    						echo "<font color=green> " . TimeDiff::dateDiff("now", "$data_topic_date", 1) . " ago</font> ";
                // Display post number
                echo " <span class='label label-secondary'>Topic</span> ";
    					echo "</div>";
    				echo "</div>";
    			echo "</div>";
          //Format the content with bbcode
  				$data_topic_content = BBCode::getHtml($data['topic_content']);
  			echo "<div class='card-body' style='padding: 0px; overflow: hidden; height: auto;'>";
          echo "<div class='col-lg-3 col-md-3 col-sm-3 d-none d-md-block' style='padding-top: 8px; padding-bottom: 8px; float: left; padding-bottom: 1500px; margin-bottom: -1500px; text-align: left; border-right: 1px solid #cccccc;'>";
            // Display User's Stats
            // Check to see if user has a profile image
            $user_image_display = CurrentUserData::getUserImage($data['topic_creator']);
            $user_signup_display = CurrentUserData::getSignUp($data['topic_creator']);
            $user_total_posts = ForumStats::getTotalPosts($data['topic_creator']);
            if(!empty($user_image_display)){
              echo "<img src='".SITE_URL.IMG_DIR_PROFILE.$user_image_display."' class='img-fluid rounded' style='margin-bottom: 2px'>";
            }else{
              echo "<span class='fas fa-user icon-size' style='margin-bottom: 2px'></span>";
            }
            echo "<hr class='mt-1 mb-1'>";
            echo " <a href='".SITE_URL."Profile/$f_p_user_name' class='btn btn-sm btn-secondary'>$f_p_user_name</a> ";
            // Check to see if current user is logged in... if not then hide the pm button
            if(!empty($data['current_userID'])){
              echo " <a href='".SITE_URL."Messages/New/$f_p_user_name' class='btn btn-sm btn-secondary'>PM</a> ";
            }
            echo "<br>";
            // Show user's online status
            $user_status = CurrentUserData::getUserStatus($data['topic_creator']);
            echo "<font size='2'>".$user_status."</font><Br>";
            // Show user's membership status
            foreach(CurrentUserData::getUserGroups($data['topic_creator']) as $row){ echo "<font size='2'>".$row."</font><br>"; };
            echo "<font size='1'>";
              echo "<strong>Total Posts</strong>: $user_total_posts<br>";
              echo "<strong>Joined</strong>: $user_signup_display";
            echo "</font>";
          echo "</div>";
          echo "<div class='col-lg-9 col-md-9 col-sm-9' style='padding-top: 8px; padding-bottom: 8px; float: left; padding-bottom: 1500px; margin-bottom: -1500px;'>";
            // Display Post DateTime
            echo "<font size='1'><strong>Posted:</strong> ".date("F d, Y @ h:i A ",strtotime($data['topic_date']))." </font><hr style='margin-top: 0px'>";
            // Display Topic Content
            if($data['action'] == "edit_topic" && $data['current_userID'] == $data['topic_creator']){
              echo "<font color='green' size='0.5'><b>Editing Topic</b></font>";
              echo Form::open(array('method' => 'post'));
              echo "
              <div class='input-group' style='margin-bottom: 25px'>
                <div class='input-group-prepend'>
                  <span class='input-group-text'><i class='fas fa-book'></i></span>
                </div>
              ";
              echo Form::input(array('type' => 'text', 'name' => 'forum_title', 'class' => 'form-control', 'value' => $data['title'], 'placeholder' => 'Topic Title', 'maxlength' => '100'));
              echo "</div>";
              ?>
              <!-- BBCode Buttons -->
              <?=BBCode::displayButtons('forum_content')?>
              <div class='input-group mb-3' style='margin-bottom: 25px'>
                <div class='input-group-prepend'>
                  <span class='input-group-text'>
                    <i class="fa fa-pencil-alt"></i>
                  </span>
                </div>
              <?php
              echo Form::textBox(array('type' => 'text', 'id' => 'forum_content', 'name' => 'forum_content', 'class' => 'form-control', 'value' => $data['topic_content'], 'placeholder' => 'Topic Content', 'rows' => '6'));
              echo "</div>";
              // Topic Reply Edit True
              echo "<input type='hidden' name='action' value='update_topic' />";
              // CSRF Token
              echo "<input type='hidden' name='token_forum' value='".$data['csrf_token']."' />";
              // Display Submit Button
              echo "<button class='btn btn-sm btn-success' name='submit' type='submit'>Update Topic</button>";
              echo Form::close();
            }else{
              // Display Topic Content
    				  echo "<div class='forum'>".$data_topic_content."</div>";
              echo "<br><br>";
              // Get user's Signature
              $user_signature = CurrentUserData::getUserSignature($data['topic_creator']);
              if(!empty($user_signature)){
                echo "<fieldset class='border border-secondary p-2'><legend class='w-auto'><i>Signature</i></font></legend> $user_signature</fieldset>";
              }
            }
            // Check to see if there are any images attaced to this post
            if(!empty($data['forum_topic_images'])){
              echo "<fieldset class='border border-secondary p-2'><legend class='w-auto'><i>Image Attachments</i></legend>";
              echo "<div class='row'>";
              foreach($data['forum_topic_images'] as $check_for_image){
                /** Display Image **/
                echo "<div class='col-lg-2 col-md-3 col-sm-4 col-xs-6' style='padding-bottom: 6px'>";
                echo "<a href='".SITE_URL."{$check_for_image->imageLocation}{$check_for_image->imageName}' data-lightbox='topic'><img id='myImg' class='img-thumbnail' src='".SITE_URL."{$check_for_image->imageLocation}{$check_for_image->imageName}' style='height: 100px'></a>";
                echo "</div>";
              }
              echo "</div></fieldset>";
            }
            echo "<br>";
          echo "</div>";
  			echo "</div>";
  			echo "<div class='card-footer text-muted'>";
  				echo "<div class='row'>";
  					echo "<div class='col-lg-6 col-md-6 col-sm-6' style='text-align:left'>";
  						if($data['topic_edit_date'] != NULL){
  							// Display how long ago this was posted
  							$timestart = $data['topic_edit_date'];  //Time of post
  							echo " <font color=red>Edited</font><font color=red> " . TimeDiff::dateDiff("now", "$timestart", 1) . " ago</font> ";
  						}
  					echo "</div>";
  					echo "<div class='col-lg-6 col-md-6 col-sm-6' style='text-align:right'>";
              /** Check to see if Sweets helper is installed **/
              if($DispenserModel->checkDispenserEnabled('Sweets')){
    						//Start Sweet
                $sweet_url = "Forum/Topic/".$data['topic_id'];
                echo Sweets::displaySweetsButton($data['topic_id'], 'Forum_Topic', $data['current_userID'], "0", $sweet_url);
                echo Sweets::getSweets($data['topic_id'], 'Forum_Topic');
              }
              // If user owns this content show forum buttons for edit and delete
              // Hide button if they are currently editing this topic or any replys
              if($data['action'] != "edit_reply" && $data['action'] != "edit_topic" && $data['current_userID'] == $data['topic_userID']){
                echo Form::open(array('method' => 'post', 'style' => 'display:inline'));
                // Topic Reply Edit True
                echo "<input type='hidden' name='action' value='edit_topic' />";
                // CSRF Token
                echo "<input type='hidden' name='token_forum' value='".$data['csrf_token']."' />";
                // Display Submit Button
                echo "<button class='btn btn-sm btn-info' name='submit' type='submit'>Edit Topic</button>";
                echo Form::close();
              }
  					echo "</div>";
  				echo "</div>"; // End row
  			echo "</div>";
  		echo "</div>";  // END panel

      // Display Paginator Links
      // Check to see if there is more than one page
      if($data['pageLinks'] > "1"){
        echo "<div class='card border-info mb-3'>";
          echo "<div class='card-header h6 text-center'>";
            echo $data['pageLinks'];
          echo "</div>";
        echo "</div>";
      }

        // Set starting post number
        if($data['current_page'] <= "1"){
            $post_num = "0";
        }else{
            $post_num = ($data['topic_reply_limit'] * $data['current_page']) - $data['topic_reply_limit'];
        }
        foreach($data['topic_replys'] as $row)
      	{
          $rf_p_main_id = $row->id;
          $rf_p_id = $row->fpr_post_id;
          $rf_p_id_cat = $row->fpr_id;
          $rf_p_content = $row->fpr_content;
          $rf_p_edit_date = $row->fpr_edit_date;
          $rf_p_timestamp = $row->fpr_timestamp;
          $rf_p_user_id = $row->fpr_user_id;
          $rf_p_allow = $row->allow;
          $rf_p_hide_userID = $row->hide_userID;
          $rf_p_hide_reason = $row->hide_reason;
          $rf_p_hide_timestamp = $row->hide_timestamp;
          $rf_p_user_name = CurrentUserData::getUserName($rf_p_user_id);
          $rf_p_hide_user_name = CurrentUserData::getUserName($rf_p_hide_userID);
          $post_num ++;

          echo "<a class='anchor' name='topicreply$rf_p_main_id'></a>";

					// Reply Topic Display
					echo "<div class='card border-secondary mb-3'>";
						echo "<div class='card-header h4'>";
							echo "<div class='row'>";
								echo "<div class='col-lg-6 col-md-6 col-sm-6'>";
									echo " Reply By: ";
									// Show user main pic
									echo " <a href='".SITE_URL."Profile/$rf_p_user_id'>$rf_p_user_name</a> ";
								echo "</div>";
								echo "<div class='col-lg-6 col-md-6 col-sm-6' style='text-align:right'>";
									// Display how long ago this was posted
									$timestart = "$rf_p_timestamp";  //Time of post
									echo "<font color=green> " . TimeDiff::dateDiff("now", "$timestart", 1) . " ago</font> ";
                  // Display reply post number
                  echo " <span class='label label-secondary'>#$post_num</span> ";
								echo "</div>";
							echo "</div>";
						echo "</div>";
						echo "<div class='card-body' style='overflow: hidden; height: auto; padding: 0px;'>";
							//Format the content with bbcode
							$rf_p_content_bb = BBCode::getHtml($rf_p_content);
              echo "<div class='col-lg-3 col-md-3 col-sm-3 d-none d-md-block' style='padding-top: 8px; padding-bottom: 8px; float: left; padding-bottom: 1500px; margin-bottom: -1500px; text-align: left; border-right: 1px solid #cccccc;'>";
                // Display User's Stats
                // Check to see if user has a profile image
                $user_image_display = CurrentUserData::getUserImage($rf_p_user_id);
                $user_signup_display = CurrentUserData::getSignUp($rf_p_user_id);
                $user_total_posts = ForumStats::getTotalPosts($rf_p_user_id);
                if(!empty($user_image_display)){
                  echo "<img src='".SITE_URL.IMG_DIR_PROFILE.$user_image_display."' class='img-fluid rounded' style='margin-bottom: 2px'>";
                }else{
                  echo "<span class='fas fa-user icon-size' style='margin-bottom: 2px'></span>";
                }
                echo "<hr class='mt-1 mb-1'>";
                echo " <a href='".SITE_URL."Profile/".$rf_p_user_name."' class='btn btn-sm btn-secondary'>$rf_p_user_name</a>";
                // Check to see if current user is logged in... if not then hide the pm button
                if(!empty($data['current_userID'])){
                  echo " <a href='".SITE_URL."Messages/New/$rf_p_user_name' class='btn btn-sm btn-secondary'>PM</a> ";
                }
                echo "<br>";
                // Show user's online status
                $user_status = CurrentUserData::getUserStatus($rf_p_user_id);
                echo "<font size='2'>".$user_status."</font><Br>";
                // Show user's membership status
                foreach(CurrentUserData::getUserGroups($rf_p_user_id) as $row){ echo "<font size='2'>".$row."</font><br>"; };
                echo "<font size='1'>";
                  echo "<strong>Total Posts</strong>: $user_total_posts<br>";
                  echo "<strong>Joined</strong>: $user_signup_display";
                echo "</font>";
              echo "</div>";
              echo "<div class='col-lg-9 col-md-9 col-sm-9' style='padding-top: 8px; padding-bottom: 8px; float: left; padding-bottom: 1500px; margin-bottom: -1500px;'>";
                // Display Post DateTime
                echo "<font size='1'><strong>Posted:</strong> ".date("F d, Y @ h:i A ",strtotime($rf_p_timestamp))." </font><hr style='margin-top: 0px'>";
                // Check to see if mod/admin has disallowed this reply
                if($rf_p_allow == "TRUE"){
                  // Check to see if user is trying to edit this reply
                  // Make sure user owns this reply before they can edit it
                  // Make sure this is the reply user is trying to edit
                  if($data['action'] == "edit_reply" && $data['current_userID'] == $rf_p_user_id && $data['edit_reply_id'] == $rf_p_main_id){
                    echo "<font color='green' size='0.5'><b>Editing Topic Reply</b></font>";
                    echo Form::open(array('method' => 'post', 'action' => '#topicreply'.$rf_p_main_id));
                ?>
                <!-- BBCode Buttons -->
                <?=BBCode::displayButtons('fpr_content')?>
                <div class='input-group mb-3' style='margin-bottom: 25px'>
                  <div class='input-group-prepend'>
                    <span class='input-group-text'>
                      <i class="fa fa-pencil-alt"></i>
                    </span>
                  </div>
                <?php
                    echo Form::textBox(array('type' => 'text', 'id' => 'fpr_content', 'name' => 'fpr_content', 'class' => 'form-control', 'value' => $rf_p_content, 'placeholder' => 'Topic Reply Content', 'rows' => '6'));
                    echo "</div>";
                    // Topic Reply Edit True
                    echo "<input type='hidden' name='action' value='update_reply' />";
                    // Topic Reply ID for editing
                    echo "<input type='hidden' name='edit_reply_id' value='".$rf_p_main_id."' />";
                    // CSRF Token
                    echo "<input type='hidden' name='token_forum' value='".$data['csrf_token']."' />";
                    // Display Submit Button
                    echo "<button class='btn btn-sm btn-success' name='submit' type='submit'>Update Reply</button>";
                    echo Form::close();
                  }else{
                    // Display Topic Reply Content
    							  echo "<div class='forum'>".$rf_p_content_bb."</div>";
                    echo "<Br><br>";
                    // Get user's Signature
                    $user_signature = CurrentUserData::getUserSignature($rf_p_user_id);
                    if(!empty($user_signature)){
                      echo "<fieldset class='border border-secondary  p-2'><legend class='w-auto'><i>Signature</i></font></legend> $user_signature</fieldset>";
                    }
                  }
                  // Check to see if there are any images attaced to this post
                  $check_for_images = $model->getForumImagesTopicReply($rf_p_id, $rf_p_main_id);
                  if(!empty($check_for_images)){
                    echo "<fieldset class='border border-secondary  p-2'><legend class='w-auto'><i>Image Attachments</i></legend>";
                    echo "<div class='row'>";
                    foreach($check_for_images as $check_for_image){
                      /** Display Image **/
                      echo "<div class='col-lg-2 col-md-3 col-sm-4 col-xs-6' style='padding-bottom: 6px'>";
                      echo "<a href='".SITE_URL."{$check_for_image->imageLocation}{$check_for_image->imageName}' data-lightbox='topicreply{$rf_p_main_id}'><img id='myImg' class='img-thumbnail' src='".SITE_URL."{$check_for_image->imageLocation}{$check_for_image->imageName}' style='height: 100px'></a>";
                      echo "</div>";
                    }
                    echo "</div></fieldset>";
                  }
                  echo "<br>";
                }else{
                  // Mod/Admin has disallowed this reply.  Show message
                  echo " <strong><font color='red'>This Reply Has Been Disabled By Mod/Admin.  Contact Mod/Admin for more information.</font></strong> ";
                  echo "<hr>";
                  echo "<b>Reply Hidden By</b>: $rf_p_user_name <b>@</b> $rf_p_hide_timestamp";
                }
              echo "</div>";
						echo "</div>";
						echo "<div class='card-footer text-muted' style='text-align:right'>";
							echo "<div class='row'>";
								echo "<div class='col-lg-6 col-md-6 col-sm-6' style='text-align:left'>";
									if($rf_p_edit_date != NULL){
										// Display how long ago this was posted
										$timestart = "$rf_p_edit_date";  //Time of post
										echo " <font color=red>Edited</font> <font color=red> " . TimeDiff::dateDiff("now", "$timestart", 1) . " ago</font> ";
									}
								echo "</div>";
								echo "<div class='col-lg-6 col-md-6 col-sm-6' style='text-align:right'>";
                  /** Check to see if Sweets helper is installed **/
                  if($DispenserModel->checkDispenserEnabled('Sweets')){
  									//Start Sweet
                    $sweet_url = "Forum/Topic/".$data['topic_id']."/".$data['current_page']."/#topicreply".$rf_p_main_id;
                    echo Sweets::displaySweetsButton($data['topic_id'], 'Forum_Topic_Reply', $data['current_userID'], $rf_p_main_id, $sweet_url);
                    echo Sweets::getSweets($data['topic_id'], 'Forum_Topic_Reply', $rf_p_main_id);
                  }
                  // If user owns this content show forum buttons for edit and delete
                  // Hide button if they are currently editing this reply
                  if($data['action'] != "edit_reply" && $data['action'] != "edit_topic" && $data['current_userID'] == $rf_p_user_id && $rf_p_allow == "TRUE"){
                    echo Form::open(array('method' => 'post', 'action' => '#topicreply'.$rf_p_main_id, 'style' => 'display:inline'));
                    // Topic Reply Edit True
                    echo "<input type='hidden' name='action' value='edit_reply' />";
                    // Topic Reply ID for editing
                    echo "<input type='hidden' name='edit_reply_id' value='".$rf_p_main_id."' />";
                    // CSRF Token
                    echo "<input type='hidden' name='token_forum' value='".$data['csrf_token']."' />";
                    // Display Submit Button
                    echo "<button class='btn btn-sm btn-info' name='submit' type='submit'>Edit Reply</button>";
                    echo Form::close();
                  }
                  // Display Admin Hide/UnHide Button
                  // Check if Admin
                  if($data['is_admin'] == true || $data['is_mod'] == true){
                    echo Form::open(array('method' => 'post', 'style' => 'display: inline;'));
                      if($rf_p_allow == "FALSE"){
                        // UnHide Button
                        $reply_url = "Topic/".$data['topic_id']."/".$data['current_page']."/#topicreply".$rf_p_main_id;
                        echo "<input type='hidden' name='reply_url' value='$reply_url' />";
                        echo "<input type='hidden' name='action' value='unhide_reply' />";
                        echo "<input type='hidden' name='reply_id' value='$rf_p_main_id' />";
                        echo "<button name='submit' type='submit' class='btn btn-sm btn-warning'>UnHide Reply</button>";
                      }else{
                        // Hide Button
                        $reply_url = "Topic/".$data['topic_id']."/".$data['current_page']."/#topicreply".$rf_p_main_id;
                        echo "<input type='hidden' name='reply_url' value='$reply_url' />";
                        echo "<input type='hidden' name='action' value='hide_reply' />";
                        echo "<input type='hidden' name='reply_id' value='$rf_p_main_id' />";
                        echo "<button name='submit' type='submit' class='btn btn-sm btn-danger' style='margin-top: 2px'>Hide Reply</button>";
                      }
                    // CSRF Token
                    echo "<input type='hidden' name='token_forum' value='".$data['csrf_token']."' />";
                    echo Form::close();
                  }
								echo "</div>";
							echo "</div>"; // End row
						echo "</div>";
					echo "</div>";

      	}

        // Display Locked Message if Topic has been locked by admin
        if($data['topic_status'] == 2){
          echo " <strong><font color='red'>Topic Locked - Replies are Disabled</font></strong> ";
        }else if($data['action'] == "edit_topic" || $data['action'] == "edit_reply"){
          // Hide reply while editing
        }else{
          // Display Create New Topic Reply Button if user is logged in
          if($data['isLoggedIn'] && $group_forum_perms_post){
?>
            <hr>
            <?php echo Form::open(array('method' => 'post',  'files' => '')); ?>

            <!-- BBCode Buttons -->
            <?=BBCode::displayButtons('fpr_content')?>
            <!-- Reply Content -->
            <div class='input-group mb-3' style='margin-bottom: 25px'>
              <div class='input-group-prepend'>
                <span class='input-group-text'>
                  <i class="fa fa-pencil-alt"></i>
                </span>
              </div>
              <?php (isset($data['fpr_content'])) ? $data['fpr_content'] = $data['fpr_content'] : $data['fpr_content'] = ""; ?>
              <?php echo Form::textBox(array('type' => 'text', 'id' => 'fpr_content', 'name' => 'fpr_content', 'class' => 'form-control', 'value' => $data['fpr_content'], 'placeholder' => 'Topic Reply Content', 'rows' => '6')); ?>
            </div>

            <?php
              // Check to see if user is a new user.  If so then disable image uploads
              if($data['is_new_user'] != true){
             ?>
                <!-- Image Upload -->
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroupFileAddon01"><i class='fas fa-image'></i></span>
                  </div>
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" accept="image/jpeg, image/gif, image/x-png" id="forumImage" name="forumImage[]" aria-describedby="inputGroupFileAddon01" multiple="multiple">
                    <label class="custom-file-label" for="inputGroupFile01">Select Image File</label>
                  </div>
                </div>
            <?php } ?>

              <!-- CSRF Token -->
              <input type="hidden" id="token_forum" name="token_forum" value="<?php echo $data['csrf_token']; ?>" />
              <input type="hidden" id="topic_id" name="topic_id" value="<?php echo $data['topic_id']; ?>" />
              <input type="hidden" id="fpr_post_id" name="fpr_post_id" value="<?php echo $data['user_reply_id']; ?>" />
              <input type='hidden' name='action' value='new_reply' />
              <button class="btn btn-md btn-success" name="submit" type="submit" id="submit">
                <?php // echo Language::show('update_profile', 'Auth'); ?>
                Submit New Reply
              </button>
            <?php echo Form::close(); ?>
            <div id="autoSave"></div>
            <div id="fpr_post_id"></div>
            <hr>
<?php
          }
        }

        // Display Paginator Links
        // Check to see if there is more than one page
        if($data['pageLinks'] > "1"){
          echo "<div class='card border-info mb-3'>";
            echo "<div class='card-header h6 text-center'>";
              echo $data['pageLinks'];
            echo "</div>";
          echo "</div>";
        }

        // Check to see if user is logged in
        if($data['current_userID']){
          // Display Subscribe UnSubscribe Button for Email Notifications.
          if($data['is_user_subscribed'] == true && $data['checkUserPosted'] == true){
            echo " You are subscribed to receive E-Mail notifications on this topic. ";
            echo Form::open(array('method' => 'post'));
            echo "<input type='hidden' name='action' value='unsubscribe' />";
            echo "<input type='hidden' name='token_forum' value='".$data['csrf_token']."' />";
            echo "<button type='submit' name='submit' class='btn btn-warning btn-sm'>UnSubscribe</button>";
            echo Form::close();
          }else {
            if($data['is_user_subscribed'] != true && $data['checkUserPosted'] == true){
              echo " You are NOT subscribed to receive E-Mail notifications on this topic. ";
              // Display subscribe button if user has posted in this topic
              echo Form::open(array('method' => 'post'));
              echo "<input type='hidden' name='action' value='subscribe' />";
              echo "<input type='hidden' name='token_forum' value='".$data['csrf_token']."' />";
              echo "<button type='submit' name='submit' class='btn btn-success btn-sm'>Subscribe</button>";
              echo Form::close();
            }
          }
        }

        // Display Admin Lock/UnLock Button
        // Check if Admin
        if($group_forum_perms_mod == true || $group_forum_perms_admin == true){
          echo "<br>";
          echo Form::open(array('method' => 'post'));
            if($data['topic_status'] == 2){
              // UnLock Button
              echo "<input type='hidden' name='action' value='unlock_topic' />";
              echo "<button name='submit' type='submit' class='btn btn-sm btn-warning'>UnLock Topic</button>";
            }else{
              // Lock Button
              echo "<input type='hidden' name='action' value='lock_topic' />";
              echo "<button name='submit' type='submit' class='btn btn-sm btn-danger'>Lock Topic</button>";
            }
          // CSRF Token
          echo "<input type='hidden' name='token_forum' value='".$data['csrf_token']."' />";
          echo Form::close();
          echo " ";

        }
      // If topic is not allowed show message and hide everything related to that post
      }else{
        echo " <strong><font color='red'>This Topic Has Been Disabled By Mod/Admin.  Contact Mod/Admin for more information.</font></strong> ";
      }
      // Display Admin Hide/UnHide Button
      // Check if Admin
      if($group_forum_perms_mod == true || $group_forum_perms_admin == true){
        echo "<hr>";
        echo Form::open(array('method' => 'post'));
          if($data['topic_allow'] == "FALSE"){
            // UnHide Button
            echo "<input type='hidden' name='action' value='unhide_topic' />";
            echo "<button name='submit' type='submit' class='btn btn-sm btn-warning'>UnHide Topic</button>";
          }else{
            // Hide Button
            echo "<input type='hidden' name='action' value='hide_topic' />";
            echo Form::input(array('type' => 'text', 'name' => 'hide_reason', 'class' => 'form-control', 'placeholder' => 'Reason For Hiding This Topic', 'maxlength' => '255'));
            echo "<button name='submit' type='submit' class='btn btn-sm btn-danger' style='margin-top: 2px'>Hide Topic</button>";
          }
        // CSRF Token
        echo "<input type='hidden' name='token_forum' value='".$data['csrf_token']."' />";
        echo Form::close();
      }
				?>
		</div>
	</div>

    <?php
        /* Get Forum Permissions Data */
        $gfp_post = $group_forum_perms_post ? "can" : "cannot";
        $gfp_mod = $group_forum_perms_mod ? "can" : "cannot";
        $gfp_admin = $group_forum_perms_admin ? "<b>can</b> <a href='".SITE_URL."AdminPanel-Forum/Settings'>administrate</a>" : "<b>cannot</b> administrate";
    ?>

    <div class='card mb-3'>
        <div class='card-header h4'>
            Forum Permissions
        </div>
        <div class='card-body'>
            You <b><?php echo $gfp_post; ?></b> post in this forum.<Br>
            You <b><?php echo $gfp_mod; ?></b> moderate this forum.<br>
            You <?php echo $gfp_admin; ?> this forum.<br>
            <?php
              if($data['current_userID']){
                if($data['update_tracking'] > 0){
                  echo "Forum Topic User Tracking Updated.";
                }
              }
            ?>
        </div>
    </div>

</div>

<?php } ?>
