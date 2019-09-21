<?php
/**
* UserApplePie v4 Forum View Plugin Admin Settings
*
* UserApplePie - Forum Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.2 for UAP v.4.3.0
*/

/** Forum Settings Admin Panel View **/

// Check to make sure admin is trying to update user profile
		if(isset($_POST['submit'])){
      /* Check to see if site is a demo site */
      if(DEMO_SITE != 'TRUE'){
  			// Check to make sure the csrf token is good
  			if (Csrf::isTokenValid('ForumAdmin')) {
          // Check to see if admin is editing forum global settings
          if($_POST['update_global_settings'] == "true"){
            // Get data from post
            $forum_on_off = Request::post('forum_on_off');
            if($forum_on_off != 'Enabled'){ $forum_on_off = 'Disabled'; }
            $forum_title = Request::post('forum_title');
            $forum_description = Request::post('forum_description');
            $forum_topic_limit = Request::post('forum_topic_limit');
            $forum_topic_reply_limit = Request::post('forum_topic_reply_limit');
            $forum_posts_group_change_enable = Request::post('forum_posts_group_change_enable');
            if($forum_posts_group_change_enable != 'true'){ $forum_posts_group_change_enable = 'false'; }
            $forum_posts_group_change = Request::post('forum_posts_group_change');
            $forum_max_image_size = Request::post('forum_max_image_size');
            // Run Forum Settings Update
            if($forum->updateGlobalSettings($forum_on_off,$forum_title,$forum_description,$forum_topic_limit,$forum_topic_reply_limit,$forum_posts_group_change_enable,$forum_posts_group_change,$forum_max_image_size)){
              // Success
              SuccessMessages::push('You Have Successfully Updated Forum Global Settings', 'AdminPanel-Forum/Settings');
            }else{
              /** Error Message **/
              ErrorMessages::push('There was an Error Updating Forum Global Settings', 'AdminPanel-Forum/Settings');
            }
          }
          // Check to see if admin is editing forum groups
          if($_POST['remove_group_user'] == "true"){
            $forum_edit_group = "users";
            $forum_edit_group_action = "remove";
          }else if($_POST['add_group_user'] == "true"){
            $forum_edit_group = "users";
            $forum_edit_group_action = "add";
          }else if($_POST['remove_group_mod'] == "true"){
            $forum_edit_group = "mods";
            $forum_edit_group_action = "remove";
          }else if($_POST['add_group_mod'] == "true"){
            $forum_edit_group = "mods";
            $forum_edit_group_action = "add";
          }else if($_POST['remove_group_admin'] == "true"){
            $forum_edit_group = "admins";
            $forum_edit_group_action = "remove";
          }else if($_POST['add_group_admin'] == "true"){
            $forum_edit_group = "admins";
            $forum_edit_group_action = "add";
          }
          if(isset($forum_edit_group) && isset($forum_edit_group_action)){
            // Get data from post
            $groupID = Request::post('groupID');
            // Updates current user's group
            if($forum->editForumGroup($forum_edit_group, $forum_edit_group_action, $groupID)){
              // Success
              SuccessMessages::push('You Have Successfully Updated Forum Group ('.$forum_edit_group.')', 'AdminPanel-Forum/Settings');
            }else{
              /* Error Message Display */
            	ErrorMessages::push('Edit Forum Group Failed', 'AdminPanel-Forum/Settings');
            }
          }
        }
      }else{
      	/* Error Message Display */
      	ErrorMessages::push('Demo Limit - Forum Settings Disabled', 'AdminPanel-Forum/Settings');
      }
    }


    // Get data for users
    $data['current_page'] = $_SERVER['REQUEST_URI'];
    $data['title'] = "Forum Global Settings";
    $data['welcome_message'] = "Welcome to the Forum Settings Admin Panel";

    // Get data for global forum settings
    $data['forum_on_off'] = $forum->globalForumSetting('forum_on_off');
    $data['forum_title'] = $forum->globalForumSetting('forum_title');
    $data['forum_description'] = $forum->globalForumSetting('forum_description');
    $data['forum_topic_limit'] = $forum->globalForumSetting('forum_topic_limit');
    $data['forum_topic_reply_limit'] = $forum->globalForumSetting('forum_topic_reply_limit');
    $data['forum_posts_group_change_enable'] = $forum->globalForumSetting('forum_posts_group_change_enable');
    $data['forum_posts_group_change'] = $forum->globalForumSetting('forum_posts_group_change');
    $data['forum_max_image_size'] = $forum->globalForumSetting('forum_max_image_size');

    // Get user groups data
    $data_groups = $model->getAllGroups();
    ////////////////////////////////////////////////////////////////////////////
    // Forum Users
    // Get groups forum user is and is not member of
    foreach ($data_groups as $value) {
      $data_forum_users_groups = $forum->checkGroupForum('users', $value->groupID);
      if($data_forum_users_groups){
        $f_users_member[] = $value->groupID;
      }else{
        $f_users_notmember[] = $value->groupID;
      }
    }
    // Gether group data for group user is member of
    if(isset($f_users_member)){
      foreach ($f_users_member as $value) {
        $f_users_member_data[] = $model->getGroupData($value);
      }
    }else{
        $f_users_member_data = "";
    }
    // Push group data to view
    $data['f_users_member_groups'] = $f_users_member_data;
    // Gether group data for group user is not member of
    if(isset($f_users_notmember)){
      foreach ($f_users_notmember as $value) {
        $f_users_notmember_groups[] = $model->getGroupData($value);
      }
    }else{
        $f_users_notmember_groups = "";
    }
    // Push group data to view
    $data['f_users_notmember_groups'] = $f_users_notmember_groups;
    ////////////////////////////////////////////////////////////////////////////
    // Forum Mods
    // Get groups forum user is and is not member of
    foreach ($data_groups as $value) {
      $data_forum_mods_groups = $forum->checkGroupForum('mods', $value->groupID);
      if($data_forum_mods_groups){
        $f_mods_member[] = $value->groupID;
      }else{
        $f_mods_notmember[] = $value->groupID;
      }
    }
    // Gether group data for group user is member of
    if(isset($f_mods_member)){
      foreach ($f_mods_member as $value) {
        $f_mods_member_data[] = $model->getGroupData($value);
      }
    }else{
        $f_mods_member_data = "";
    }
    // Push group data to view
    $data['f_mods_member_groups'] = $f_mods_member_data;
    // Gether group data for group user is not member of
    if(isset($f_mods_notmember)){
      foreach ($f_mods_notmember as $value) {
        $f_mods_notmember_groups[] = $model->getGroupData($value);
      }
    }
    // Push group data to view
    $data['f_mods_notmember_groups'] = $f_mods_notmember_groups;
    ////////////////////////////////////////////////////////////////////////////
    // Forum Admins
    // Get groups forum user is and is not member of
    foreach ($data_groups as $value) {
      $data_forum_admins_groups = $forum->checkGroupForum('admins', $value->groupID);
      if($data_forum_admins_groups){
        $f_admins_member[] = $value->groupID;
      }else{
        $f_admins_notmember[] = $value->groupID;
      }
    }
    // Gether group data for group user is member of
    if(isset($f_admins_member)){
      foreach ($f_admins_member as $value) {
        $f_admins_member_data[] = $model->getGroupData($value);
      }
    }else{
        $f_admins_member_data = "";
    }
    // Push group data to view
    $data['f_admins_member_groups'] = $f_admins_member_data;
    // Gether group data for group user is not member of
    if(isset($f_admins_notmember)){
      foreach ($f_admins_notmember as $value) {
        $f_admins_notmember_groups[] = $model->getGroupData($value);
      }
    }
    // Push group data to view
    $data['f_admins_notmember_groups'] = $f_admins_notmember_groups;
    ////////////////////////////////////////////////////////////////////////////

    // Setup CSRF token
    $data['csrf_token'] = Csrf::makeToken('ForumAdmin');

    // Setup Breadcrumbs
    $data['breadcrumbs'] = "<li class='breadcrumb-item'><a href='".SITE_URL."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li><li class='breadcrumb-item active'><i class='fa fa-fw fa-cog'></i> ".$data['title']."</li>";

?>

<div class='col-lg-12 col-md-12 col-sm-12'>
	<div class='card mb-3'>
		<div class='card-header h4'>
			<?php echo $data['title'];  ?>
		</div>
		<div class='card-body'>
			<p><?php echo $data['welcome_message'] ?></p>

      <!-- Start main forum Settings -->
        <?php echo Form::open(array('method' => 'post')); ?>

        <!-- Enable / Disable Forum -->
        <div class='input-group mb-3' style='margin-bottom: 25px'>
          <div class="input-group-prepend">
            <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Forum Enable</span>
          </div>
          <label class='switch form-control'>
            <input type="checkbox" class='form-control' id='forum_on_off' name='forum_on_off' value="Enabled" <?php if($data['forum_on_off'] == "Enabled"){echo "CHECKED";}?> />
            <span class="slider block"></span>
          </label>
          <?php echo PageFunctions::displayPopover('Forum Enable/Disable', 'Default: Enabled - Turn the Forum ON(Enable) or OFF(Disable). Hides the Forum from all users if Disabled.', true, 'input-group-text'); ?>
        </div>

        <!-- Forum Name -->
        <div class='input-group mb-3'>
          <div class='input-group-prepend'>
            <span class='input-group-text' id='basic-addon1'><i class='fa fa-fw fa-cog'></i> Forum Title</span>
          </div>
          <?php echo Form::input(array('type' => 'text', 'name' => 'forum_title', 'class' => 'form-control', 'value' => $data['forum_title'], 'placeholder' => 'Global Forum Name/Title', 'maxlength' => '100')); ?>
          <?php echo PageFunctions::displayPopover('Forum Title', 'Default: Forum - Sets the title of the forum.', true, 'input-group-text'); ?>
        </div>

        <!-- Forum Description -->
        <div class='input-group mb-3'>
          <div class='input-group-prepend'>
            <span class='input-group-text' id='basic-addon1'><i class='fa fa-fw fa-cog'></i> Forum Description</span>
          </div>
          <?php echo Form::textBox(array('type' => 'text', 'name' => 'forum_description', 'class' => 'form-control', 'value' => $data['forum_description'], 'placeholder' => 'Global Forum Description', 'maxlength' => '255')); ?>
          <?php echo PageFunctions::displayPopover('Forum Description', 'Default: Blank - Sets the description of the forum.', true, 'input-group-text'); ?>
        </div>

        <hr>

        <!-- Forum Topic Limit Per Page -->
        <div class='input-group mb-3'>
          <div class='input-group-prepend'>
            <span class='input-group-text' id='basic-addon1'><i class='fa fa-fw fa-cog'></i> Topics Per Page</span>
          </div>
          <?php echo Form::input(array('type' => 'text', 'name' => 'forum_topic_limit', 'class' => 'form-control', 'value' => $data['forum_topic_limit'], 'placeholder' => 'Topics Per Page Limit', 'maxlength' => '100')); ?>
          <?php echo PageFunctions::displayPopover('Topics Per Page', 'Default: 20 - Sets the total number of topics to display per page.', true, 'input-group-text'); ?>
        </div>

        <!-- Forum Topic Reply Limit Per Page -->
        <div class='input-group mb-3'>
          <div class='input-group-prepend'>
            <span class='input-group-text' id='basic-addon1'><i class='fa fa-fw fa-cog'></i> Topic Replies Per Page</span>
          </div>
          <?php echo Form::input(array('type' => 'text', 'name' => 'forum_topic_reply_limit', 'class' => 'form-control', 'value' => $data['forum_topic_reply_limit'], 'placeholder' => 'Topic Replies Per Page Limit', 'maxlength' => '100')); ?>
          <?php echo PageFunctions::displayPopover('Topic Replies Per Page', 'Default: 10 - Sets the total number of topics to display per page.', true, 'input-group-text'); ?>
        </div>

        <hr>

        <!-- Enable / Disable Forum Auto Member Group Change -->
        <div class='input-group mb-3'>
          <div class='input-group-prepend'>
            <span class='input-group-text' id='basic-addon1'><i class='fa fa-fw fa-cog'></i> Auto Member</span>
          </div>
          <label class='switch form-control'>
            <input type="checkbox" class='form-control' id='forum_posts_group_change_enable' name='forum_posts_group_change_enable' value="true" <?php if($data['forum_posts_group_change_enable'] == "true"){echo "CHECKED";}?> />
            <span class="slider block"></span>
          </label>
          <?php echo PageFunctions::displayPopover('Auto New Member Group Change', 'Default: Enabled - When enabled, the site will automatically upgrade a user that is a member of the New Member group to the Member group when goal is reached.', true, 'input-group-text'); ?>
        </div>

        <!-- Forum Posts Group Change Limit -->
        <div class='input-group mb-3'>
          <div class='input-group-prepend'>
            <span class='input-group-text' id='basic-addon1'><i class='fa fa-fw fa-cog'></i> Auto Member Posts Limit</span>
          </div>
          <?php echo Form::input(array('type' => 'text', 'name' => 'forum_posts_group_change', 'class' => 'form-control', 'value' => $data['forum_posts_group_change'], 'placeholder' => 'New Member Group Change Posts Limit', 'maxlength' => '100')); ?>
          <?php echo PageFunctions::displayPopover('New Member Group Change Limit', 'Default: 15 - Sets the total number of posts a New Member must post to upgrade to a Member if Enabled.', true, 'input-group-text'); ?>
        </div>

        <!-- Max Image Size when uploaded to server -->
        <div class='input-group mb-3'>
          <div class='input-group-prepend'>
            <span class='input-group-text' id='basic-addon1'><i class='fa fa-fw fa-cog'></i> Forum Image Max Size</span>
          </div>
          <select class='form-control' id='forum_max_image_size' name='forum_max_image_size'>
            <option value='240,160' <?php if($data['forum_max_image_size'] == "240,160"){echo "SELECTED";}?> >240 x 160</option>
            <option value='320,240' <?php if($data['forum_max_image_size'] == "320,240"){echo "SELECTED";}?> >320 x 160</option>
            <option value='460,309' <?php if($data['forum_max_image_size'] == "460,309"){echo "SELECTED";}?> >460 x 309</option>
            <option value='800,600' <?php if($data['forum_max_image_size'] == "800,600"){echo "SELECTED";}?> >800 x 600</option>
            <option value='1024,768' <?php if($data['forum_max_image_size'] == "1024,768"){echo "SELECTED";}?> >1024 x 768</option>
            <option value='1920,1080' <?php if($data['forum_max_image_size'] == "1920,1080"){echo "SELECTED";}?> >1920 x 1080</option>
          </select>
          <?php echo PageFunctions::displayPopover('Forum Image Max Size', 'Default: 800x600 - Sets the max image size that the site will automatically adjust images to when uploaded.  The larger the size, the larger the file.  Use low resolution for slower bandwidth connection on server.', true, 'input-group-text'); ?>
        </div>

        <?php echo Form::input(array('type' => 'hidden', 'name' => 'token_ForumAdmin', 'value' => $data['csrf_token'])); ?>
        <?php echo Form::input(array('type' => 'hidden', 'name' => 'update_global_settings', 'value' => 'true')); ?>
        <button class='btn btn-sm btn-success' name='submit' type='submit'>Update Forum Settings</button>
        <?php echo Form::close(); ?>

      <!-- End Main forum Settings -->
    </div>
  </div>

  <div class='row'>
  <!-- Start of Forum Users groups -->
  <div class='col-lg-4 col-md-4'>
  	<div class='card mb-3'>
  		<div class='card-header h4'>
  			Forum User Group
        <?php echo PageFunctions::displayPopover('Forum User Group', 'Sets which Member Groups can Post on the forum.', false, 'btn btn-sm btn-light float-right'); ?>
  		</div>

  			<?php
          echo "<table class='table table-hover responsive'>";
            // Displays User's Groups they are a member of
            if(!empty($data['f_users_member_groups'])){
              echo "<th style='background-color: #EEE'>Groups Allowed to Post on Forum: </th>";
              foreach($data['f_users_member_groups'] as $member){
                echo "<tr><td>";
                echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                echo "<input type='hidden' name='token_ForumAdmin' value='".$data['csrf_token']."'>";
                echo "<input type='hidden' name='remove_group_user' value='true' />";
                echo "<input type='hidden' name='groupID' value='".$member[0]->groupID."'>";
                echo "<button class='btn btn-sm btn-danger' name='submit' type='submit'>Remove</button>";
                echo Form::close();
                echo " - <font color='".$member[0]->groupFontColor."' style='font-weight: ".$member[0]->groupFontWeight."'>".$member[0]->groupName."</font>";
                echo "</td></tr>";
              }
            }else{
              echo "<th style='background-color: #EEE'>Groups Allowed to Post on Forum: </th>";
              echo "<tr><td> None </td></tr>";
            }
          echo "</table>";

          echo "<table class='table table-hover responsive'>";
            // Displays User's Groups they are not a member of
            if(!empty($data['f_users_notmember_groups'])){
              echo "<th style='background-color: #EEE'>Groups NOT Allowed to Post on Forum: </th>";
              foreach($data['f_users_notmember_groups'] as $notmember){
                echo "<tr><td>";
                echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                echo "<input type='hidden' name='token_ForumAdmin' value='".$data['csrf_token']."'>";
                echo "<input type='hidden' name='add_group_user' value='true' />";
                echo "<input type='hidden' name='groupID' value='".$notmember[0]->groupID."'>";
                echo "<button class='btn btn-sm btn-success' name='submit' type='submit'>Add</button>";
                echo Form::close();
                echo " - <font color='".$notmember[0]->groupFontColor."' style='font-weight: ".$notmember[0]->groupFontWeight."'>".$notmember[0]->groupName."</font> ";
                echo "</td></tr>";
              }
            }else{
              echo "<th style='background-color: #EEE'>Groups NOT Allowed to Post on Forum: </th>";
              echo "<tr><td> None </td></tr>";
            }
          echo "</table>";

          // Display Check Box Settings For What Group Can Do on Forum
  //        echo Form::open(array('method' => 'post'));
  //        echo "<table class='table table-hover responsive'>";
  //          echo "<th style='background-color: #EEE'>Forum Users Permissions:</th>";
  //          echo "<tr><td>";
  //            echo "<input type='checkbox' name='forum_view_posts' value='true'> View Posts";
  //          echo "</td></tr><tr><td>";
  //            echo "<input type='checkbox' name='forum_create_posts' value='true'> Create New Posts";
  //          echo "</td></tr><tr><td>";
  //            echo "<input type='checkbox' name='forum_edit_posts' value='true'> Edit Their Posts";
  //          echo "</td></tr><tr><td>";
  //            echo "<input type='checkbox' name='forum_edit_any_posts' value='true'> Edit Any Posts";
  //          echo "</td></tr><tr><td>";
  //            echo "<input type='checkbox' name='forum_hide_posts' value='true'> Hide Posts";
  //          echo "</td></tr><tr><td>";
  //            echo "<input type='checkbox' name='forum_lock_topics' value='true'> Lock/Unlock Topics";
  //          echo "</td></tr>";
  //        echo "</table>";
  //        echo "<input type='hidden' name='update_users_group_perm' value='true' />";
  //        echo "<button class='btn btn-sm btn-success' name='submit' type='submit'>Update Permissions</button>";
  //        echo Form::close();
        ?>

  	</div>
  </div>

  <!-- Start of Forum Moderator groups -->
  <div class='col-lg-4 col-md-4'>
  	<div class='card mb-3'>
  		<div class='card-header h4'>
  			Forum Moderator Group
        <?php echo PageFunctions::displayPopover('Forum Moderator Group', 'Sets which Member Groups can Moderate the forum.', false, 'btn btn-sm btn-light float-right'); ?>
  		</div>

  			<?php
          echo "<table class='table table-hover responsive'>";
            // Displays User's Groups they are a member of
            if(!empty($data['f_mods_member_groups'])){
              echo "<th style='background-color: #EEE'>Groups Allowed to Moderate Forum: </th>";
              foreach($data['f_mods_member_groups'] as $member){
                echo "<tr><td>";
                echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                echo "<input type='hidden' name='token_ForumAdmin' value='".$data['csrf_token']."'>";
                echo "<input type='hidden' name='remove_group_mod' value='true' />";
                echo "<input type='hidden' name='groupID' value='".$member[0]->groupID."'>";
                echo "<button class='btn btn-sm btn-danger' name='submit' type='submit'>Remove</button>";
                echo Form::close();
                echo " - <font color='".$member[0]->groupFontColor."' style='font-weight: ".$member[0]->groupFontWeight."'>".$member[0]->groupName."</font>";
                echo "</td></tr>";
              }
            }else{
              echo "<th style='background-color: #EEE'>Groups Allowed to Moderate Forum: </th>";
              echo "<tr><td> None </td></tr>";
            }
          echo "</table>";

          echo "<table class='table table-hover responsive'>";
            // Displays User's Groups they are not a member of
            if(!empty($data['f_mods_notmember_groups'])){
              echo "<th style='background-color: #EEE'>Groups NOT Allowed to Moderate Forum: </th>";
              foreach($data['f_mods_notmember_groups'] as $notmember){
                echo "<tr><td>";
                echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                echo "<input type='hidden' name='token_ForumAdmin' value='".$data['csrf_token']."'>";
                echo "<input type='hidden' name='add_group_mod' value='true' />";
                echo "<input type='hidden' name='groupID' value='".$notmember[0]->groupID."'>";
                echo "<button class='btn btn-sm btn-success' name='submit' type='submit'>Add</button>";
                echo Form::close();
                echo " - <font color='".$notmember[0]->groupFontColor."' style='font-weight: ".$notmember[0]->groupFontWeight."'>".$notmember[0]->groupName."</font> ";
                echo "</td></tr>";
              }
            }else{
              echo "<th style='background-color: #EEE'>Groups NOT Allowed to Moderate Forum: </th>";
              echo "<tr><td> None </td></tr>";
            }
          echo "</table>";
        ?>

  	</div>
  </div>

  <!-- Start of Forum Admin groups -->
  <div class='col-lg-4 col-md-4'>
  	<div class='card mb-3'>
  		<div class='card-header h4'>
  			Forum Administrator Group
        <?php echo PageFunctions::displayPopover('Forum Administrator Group', 'Sets which Member Groups can Administrate the forum.', false, 'btn btn-sm btn-light float-right'); ?>
  		</div>

  			<?php
          echo "<table class='table table-hover responsive'>";
            // Displays User's Groups they are a member of
            if(!empty($data['f_admins_member_groups'])){
              echo "<th style='background-color: #EEE'>Groups Allowed to Admin Forum: </th>";
              foreach($data['f_admins_member_groups'] as $member){
                echo "<tr><td>";
                echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                echo "<input type='hidden' name='token_ForumAdmin' value='".$data['csrf_token']."'>";
                echo "<input type='hidden' name='remove_group_admin' value='true' />";
                echo "<input type='hidden' name='groupID' value='".$member[0]->groupID."'>";
                echo "<button class='btn btn-sm btn-danger' name='submit' type='submit'>Remove</button>";
                echo Form::close();
                echo " - <font color='".$member[0]->groupFontColor."' style='font-weight: ".$member[0]->groupFontWeight."'>".$member[0]->groupName."</font>";
                echo "</td></tr>";
              }
            }else{
              echo "<th style='background-color: #EEE'>Groups Allowed to Admin Forum: </th>";
              echo "<tr><td> None </td></tr>";
            }
          echo "</table>";

          echo "<table class='table table-hover responsive'>";
            // Displays User's Groups they are not a member of
            if(!empty($data['f_admins_notmember_groups'])){
              echo "<th style='background-color: #EEE'>Groups NOT Allowed to Admin Forum: </th>";
              foreach($data['f_admins_notmember_groups'] as $notmember){
                echo "<tr><td>";
                echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                echo "<input type='hidden' name='token_ForumAdmin' value='".$data['csrf_token']."'>";
                echo "<input type='hidden' name='add_group_admin' value='true' />";
                echo "<input type='hidden' name='groupID' value='".$notmember[0]->groupID."'>";
                echo "<button class='btn btn-sm btn-success' name='submit' type='submit'>Add</button>";
                echo Form::close();
                echo " - <font color='".$notmember[0]->groupFontColor."' style='font-weight: ".$notmember[0]->groupFontWeight."'>".$notmember[0]->groupName."</font> ";
                echo "</td></tr>";
              }
            }else{
              echo "<th style='background-color: #EEE'>Groups NOT Allowed to Admin Forum: </th>";
              echo "<tr><td> None </td></tr>";
            }
          echo "</table>";
        ?>

  	</div>
  </div>
  </div>

</div>
