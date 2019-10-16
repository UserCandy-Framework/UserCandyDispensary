<?php
/**
* UserApplePie v4 Forum View Plugin New Topic
*
* UserApplePie - Forum Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.2 for UAP v.4.3.0
*/

use Helpers\{Csrf,ErrorMessages,SuccessMessages,CurrentUserData,Request,Url,Form,BBCode,SimpleImage};

/** Forum New Topic View **/

/** Get Data from URL **/
(empty($get_var_2)) ? $id = null : $id = $get_var_2;

// Output Current User's ID
$data['current_userID'] = $u_id;

// Get Requested Topic's Title and Description
$data['forum_cat_id'] = $id;
$data['forum_cat'] = $model->forum_cat($id);
$data['forum_cat_des'] = $model->forum_cat_des($id);
$data['forum_topics'] = $model->forum_topics($id);

// Ouput Page Title
$data['title'] = "New Topic for ".$data['forum_cat'];

// Output Welcome Message
$data['welcome_message'] = "Welcome to the new topic page.";

// Check to see if current user is a new user
$data['is_new_user'] = $auth->checkIsNewUser($u_id);

// Get data from post
(isset($_POST['forum_post_id'])) ? $data['forum_post_id'] = Request::post('forum_post_id') : $data['forum_post_id'] = "";
(isset($_POST['forum_title'])) ? $data['forum_title'] = Request::post('forum_title') : $data['forum_title'] = "";
(isset($_POST['forum_content'])) ? $data['forum_content'] = Request::post('forum_content') : $data['forum_content'] = "";

// Check for autosave
if(isset($_POST['forum_topic_autosave'])){
  if($_POST['forum_topic_autosave'] == "autosave_topic"){
    /** Forum Auto Save **/
    // Check to make sure the csrf token is good
    if (Csrf::isTokenValid('forum')) {
      /** Token Good **/
      if(!empty($data['forum_post_id'])){
        //var_dump($_POST);
        $update_topic = $model->updateSavedTopic($data['forum_post_id'], $data['forum_title'], $data['forum_content']);
        //echo $update_topic;
      }else{
        /** New Forum Post - Create new post **/
        $new_topic = $model->sendTopic($u_id, $id, $data['forum_title'], $data['forum_content']);
        echo $new_topic;
      }
    }
  }
}else{

  // Check to see if user is submitting a new topic
  if(isset($_POST['submit'])){

    // Check to make sure the csrf token is good
    if (Csrf::isTokenValid('forum')) {

        // Check to make sure user completed all required fields in form
        if(empty($data['forum_title'])){
          // Username field is empty
          $error[] = 'Topic Title Field is Blank!';
        }
        if(empty($data['forum_content'])){
          // Subject field is empty
          $error[] = 'Topic Content Field is Blank!';
        }
        // Check for errors before sending message
        if(!isset($error)){
          if(!empty($data['forum_post_id'])){
            //Update if already saved as draft
            $update_topic = $model->updateSavedTopic($data['forum_post_id'], $data['forum_title'], $data['forum_content'], "1");
          }else{
            // No Errors, lets submit the new topic to db
            $new_topic = $model->sendTopic($u_id, $id, $data['forum_title'], $data['forum_content'], "1");
          }

          if(empty($new_topic)){ $new_topic = $data['forum_post_id']; }

            if($new_topic){
              // New Topic Successfully Created Now Check if User is Uploading Image
              // Check for image upload with this topic
              /** Ready site to upload Files TOPIC **/
              if(!empty($_FILES['forumImage']['name'][0])){
                $countfiles = count($_FILES['forumImage']['name']);
                for($i=0;$i<$countfiles;$i++){
                  // Check to see if an image is being uploaded
                  if(!empty($_FILES['forumImage']['tmp_name'][$i])){
                      $picture = file_exists($_FILES['forumImage']['tmp_name'][$i]) || is_uploaded_file($_FILES['forumImage']['tmp_name'][$i]) ? $_FILES ['forumImage']['tmp_name'][$i] : array ();
                      if($picture != ""){
                          // Get file size for db
                          $check = getimagesize ( $picture );
                          $img_dir_forum_topic = IMG_DIR_FORUM_TOPIC;
                          // Check to make sure image is good
                          if($check['size'] < 5000000 && $check && ($check['mime'] == "image/jpeg" || $check['mime'] == "image/png" || $check['mime'] == "image/gif")){
                              // Check to see if Img Upload Directory Exists, if not create it
                              if(!file_exists(ROOTDIR.$img_dir_forum_topic)){
                                mkdir(ROOTDIR.$img_dir_forum_topic,0777,true);
                              }
                              // Format new image and upload it to server
                              $image = new SimpleImage($picture);
                              $new_image_name = "forum-image-topic-uid{$u_id}-fid{$id}";
                              $rand_string = substr(str_shuffle(md5(time())), 0, 10);
                              $img_name = $new_image_name.'-'.$rand_string.'.gif';
                              $img_max_size = explode(',', $forum_max_image_size);
                              $image->best_fit($img_max_size[0],$img_max_size[1])->save(ROOTDIR.$img_dir_forum_topic.$img_name);
                              // Make sure image was Successfull
                              if($img_name){
                                // Add new image to database
                                if($model->sendNewImage($u_id, $img_name, $img_dir_forum_topic, $file_size, $id, $new_topic)){
                                  $img_success = "<br> Images Successfully Uploaded";
                                }else{
                                  $img_success = "<br> No Images Uploaded";
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

              // Success
              SuccessMessages::push('You Have Successfully Created a New Topic'.$img_success, 'Forum/Topic/'.$new_topic);
              $data['hide_form'] = "true";
            }else{
              // Fail
              $error[] = 'New Topic Create Failed';
            }
        }// End Form Complete Check
    }
  }else{
    // Check to see if user has unpublished work.  If so then display it.
    $data['forum_post_id'] = $model->getUnPublishedWork($data['current_userID'], $data['forum_cat_id']);
    if($data['forum_post_id']){
      $data['forum_title'] = $model->topic_title($data['forum_post_id']);
      $data['forum_content'] = $model->topic_content($data['forum_post_id']);
    }
  }

  // Output errors if any
  if(!empty($error)){ $data['error'] = $error; };

  // Get Recent Posts List for Sidebar
  $data['forum_recent_posts'] = $model->forum_recent_posts();

  // Setup Breadcrumbs
  $data['breadcrumbs'] = "<li class='breadcrumb-item'><a href='".SITE_URL."Forum'>".$forum_title."</a></li><li class='breadcrumb-item'><a href='".SITE_URL."Topics/$id'>".$data['forum_cat']."</a><li class='breadcrumb-item active'>".$data['title']."</li>";

  // Ready the token!
  $data['csrf_token'] = Csrf::makeToken('forum');

  /* Add Java Stuffs */
  $js = "<script src='".Url::templatePath()."js/bbcode.js'></script>";
  $js .= "<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js'></script>";
  $js .= "<script src='".Url::templatePath()."js/forum_autosave_topic.js'></script>";

?>

<div class='col-lg-8 col-md-8'>

	<div class='card mb-3'>
		<div class='card-header h4'>
			<?php echo $data['title'] ?>
		</div>
		<div class='card-body'>
				<?php echo $data['welcome_message']; ?>


            <?php echo Form::open(array('method' => 'post', 'files' => '')); ?>

            <!-- Topic Title -->
            <div class='input-group' style='margin-bottom: 25px'>
              <div class="input-group-prepend">
                <span class='input-group-text'><i class='fas fa-book'></i></span>
              </div>
              <?php echo Form::input(array('type' => 'text', 'id' => 'forum_title', 'name' => 'forum_title', 'class' => 'form-control', 'value' => $data['forum_title'], 'placeholder' => 'Topic Title', 'maxlength' => '100')); ?>
            </div>
            <!-- BBCode Buttons -->
            <?=BBCode::displayButtons('forum_content')?>
            <!-- Topic Content -->
            <div class='input-group' style='margin-bottom: 25px'>
              <div class="input-group-prepend">
                <span class='input-group-text'>
                  <i class="fa fa-pencil-alt"></i>
                </span>
              </div>
              <?php echo Form::textBox(array('type' => 'text', 'id' => 'forum_content', 'name' => 'forum_content', 'class' => 'form-control', 'value' => $data['forum_content'], 'placeholder' => 'Topic Content', 'rows' => '6')); ?>
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
              <input type="hidden" id="forum_cat_id" name="forum_cat_id" value="<?php echo $data['forum_cat_id']; ?>" />
              <input type="hidden" id="forum_post_id" name="forum_post_id" value="<?php echo $data['forum_post_id']; ?>" />
              <button class="btn btn-md btn-success" name="submit" type="submit" id="submit">
                <?php // echo Language::show('update_profile', 'Auth'); ?>
                Submit New Topic
              </button>
            <?php echo Form::close(); ?>
            <div id="autoSave"></div>
            <div id="forum_post_id"></div>

		</div>
	</div>
</div>

<?php } ?>
