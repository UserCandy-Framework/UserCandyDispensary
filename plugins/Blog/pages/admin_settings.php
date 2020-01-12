<?php
/**
* UserApplePie v4 Blog View Plugin Admin Settings
*
* UserApplePie - Blog Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 1.0.0 for UAP v.4.3.0
*/

/** Blog Display Page View **/

use Core\Language;
use Helpers\{ErrorMessages,SuccessMessages,Form,Request,CurrentUserData,BBCode,PageFunctions};


?>

<div class="col-lg-9 col-md-8">
  <div class="blog-post border rounded mb-4 shadow-sm p-2">
    <!-- Start Form -->
    <?php echo Form::open(array('method' => 'post', 'files' => '')); ?>

    <!-- Blog Title -->
    <div class='input-group' style='margin-bottom: 25px'>
      <div class="input-group-prepend">
        <span class='input-group-text'><i class='fas fa-fw fa-book'></i> Blog Title</span>
      </div>
      <?php echo Form::input(array('type' => 'text', 'id' => 'blog_title', 'name' => 'blog_title', 'class' => 'form-control', 'value' => $data['blog_title'], 'placeholder' => 'Blog Title', 'maxlength' => '255')); ?>
    </div>

    <!-- Blog Description -->
    <div class='input-group' style='margin-bottom: 25px'>
      <div class="input-group-prepend">
        <span class='input-group-text'><i class='fas fa-fw fa-book'></i> Blog Description</span>
      </div>
      <?php echo Form::input(array('type' => 'text', 'id' => 'blog_description', 'name' => 'blog_description', 'class' => 'form-control', 'value' => $data['blog_description'], 'placeholder' => 'Blog Description', 'maxlength' => '255')); ?>
    </div>

    <!-- Max Image Size when uploaded to server -->
    <div class='input-group mb-3'>
      <div class='input-group-prepend'>
        <span class='input-group-text' id='basic-addon1'><i class='fa fa-fw fa-cog'></i> Blog Image Max Size</span>
      </div>
      <select class='form-control' id='blog_max_image_size' name='blog_max_image_size'>
        <option value='240,160' <?php if($data['blog_max_image_size'] == "240,160"){echo "SELECTED";}?> >240 x 160</option>
        <option value='320,240' <?php if($data['blog_max_image_size'] == "320,240"){echo "SELECTED";}?> >320 x 160</option>
        <option value='460,309' <?php if($data['blog_max_image_size'] == "460,309"){echo "SELECTED";}?> >460 x 309</option>
        <option value='800,600' <?php if($data['blog_max_image_size'] == "800,600"){echo "SELECTED";}?> >800 x 600</option>
        <option value='1024,768' <?php if($data['blog_max_image_size'] == "1024,768"){echo "SELECTED";}?> >1024 x 768</option>
        <option value='1920,1080' <?php if($data['blog_max_image_size'] == "1920,1080"){echo "SELECTED";}?> >1920 x 1080</option>
      </select>
      <?php echo PageFunctions::displayPopover('Blog Image Max Size', 'Default: 800x600 - Sets the max image size that the site will automatically adjust images to when uploaded.  The larger the size, the larger the file.  Use low resolution for slower bandwidth connection on server.', true, 'input-group-text'); ?>
    </div>

    <!-- CSRF Token -->
    <input type="hidden" id="token_blog" name="token_blog" value="<?php echo $data['csrf_token']; ?>" />
    <input type="hidden" id="update_blog_settings" name="update_blog_settings" value="true" />
    <button class="btn btn-md btn-success" name="submit" type="submit" id="submit">
      Update Blog Admin Settings
    </button>
    <!-- Close Form -->
    <?php echo Form::close(); ?>

  </div>

  <div class="row">
    <!-- Start of Blog Users groups -->
    <div class='col-lg-4 col-md-4'>
    	<div class='card mb-3 border rounded mb-4 shadow-sm p-2'>
    		<div class='card-header h4'>
    			Blog User Group
          <?php echo PageFunctions::displayPopover('Blog User Group', 'Sets which Member Groups can Post on the Blog.', false, 'btn btn-sm btn-light float-right'); ?>
    		</div>

    			<?php
            echo "<table class='table table-hover responsive'>";
              // Displays User's Groups they are a member of
              if(!empty($data['b_users_member_groups'])){
                echo "<th style='background-color: #EEE'>Groups Allowed to Post on Blog: </th>";
                foreach($data['b_users_member_groups'] as $member){
                  echo "<tr><td>";
                  echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                  echo "<input type='hidden' name='token_blog' value='".$data['csrf_token']."'>";
                  echo "<input type='hidden' name='remove_group_user' value='true' />";
                  echo "<input type='hidden' name='groupID' value='".$member[0]->groupID."'>";
                  echo "<button class='btn btn-sm btn-danger' name='submit' type='submit'>Remove</button>";
                  echo Form::close();
                  echo " - <font color='".$member[0]->groupFontColor."' style='font-weight: ".$member[0]->groupFontWeight."'>".$member[0]->groupName."</font>";
                  echo "</td></tr>";
                }
              }else{
                echo "<th style='background-color: #EEE'>Groups Allowed to Post on Blog: </th>";
                echo "<tr><td> None </td></tr>";
              }
            echo "</table>";

            echo "<table class='table table-hover responsive'>";
              // Displays User's Groups they are not a member of
              if(!empty($data['b_users_notmember_groups'])){
                echo "<th style='background-color: #EEE'>Groups NOT Allowed to Post on Blog: </th>";
                foreach($data['b_users_notmember_groups'] as $notmember){
                  echo "<tr><td>";
                  echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                  echo "<input type='hidden' name='token_blog' value='".$data['csrf_token']."'>";
                  echo "<input type='hidden' name='add_group_user' value='true' />";
                  echo "<input type='hidden' name='groupID' value='".$notmember[0]->groupID."'>";
                  echo "<button class='btn btn-sm btn-success' name='submit' type='submit'>Add</button>";
                  echo Form::close();
                  echo " - <font color='".$notmember[0]->groupFontColor."' style='font-weight: ".$notmember[0]->groupFontWeight."'>".$notmember[0]->groupName."</font> ";
                  echo "</td></tr>";
                }
              }else{
                echo "<th style='background-color: #EEE'>Groups NOT Allowed to Post on Blog: </th>";
                echo "<tr><td> None </td></tr>";
              }
            echo "</table>";

          ?>

    	</div>
    </div>

    <!-- Start of Blog Moderator groups -->
    <div class='col-lg-4 col-md-4'>
    	<div class='card mb-3 border rounded mb-4 shadow-sm p-2'>
    		<div class='card-header h4'>
    			Blog Moderator Group
          <?php echo PageFunctions::displayPopover('Blog Moderator Group', 'Sets which Member Groups can Moderate the Blog.', false, 'btn btn-sm btn-light float-right'); ?>
    		</div>

    			<?php
            echo "<table class='table table-hover responsive'>";
              // Displays User's Groups they are a member of
              if(!empty($data['b_mods_member_groups'])){
                echo "<th style='background-color: #EEE'>Groups Allowed to Moderate Blog: </th>";
                foreach($data['b_mods_member_groups'] as $member){
                  echo "<tr><td>";
                  echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                  echo "<input type='hidden' name='token_blog' value='".$data['csrf_token']."'>";
                  echo "<input type='hidden' name='remove_group_mod' value='true' />";
                  echo "<input type='hidden' name='groupID' value='".$member[0]->groupID."'>";
                  echo "<button class='btn btn-sm btn-danger' name='submit' type='submit'>Remove</button>";
                  echo Form::close();
                  echo " - <font color='".$member[0]->groupFontColor."' style='font-weight: ".$member[0]->groupFontWeight."'>".$member[0]->groupName."</font>";
                  echo "</td></tr>";
                }
              }else{
                echo "<th style='background-color: #EEE'>Groups Allowed to Moderate Blog: </th>";
                echo "<tr><td> None </td></tr>";
              }
            echo "</table>";

            echo "<table class='table table-hover responsive'>";
              // Displays User's Groups they are not a member of
              if(!empty($data['b_mods_notmember_groups'])){
                echo "<th style='background-color: #EEE'>Groups NOT Allowed to Moderate Blog: </th>";
                foreach($data['b_mods_notmember_groups'] as $notmember){
                  echo "<tr><td>";
                  echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                  echo "<input type='hidden' name='token_blog' value='".$data['csrf_token']."'>";
                  echo "<input type='hidden' name='add_group_mod' value='true' />";
                  echo "<input type='hidden' name='groupID' value='".$notmember[0]->groupID."'>";
                  echo "<button class='btn btn-sm btn-success' name='submit' type='submit'>Add</button>";
                  echo Form::close();
                  echo " - <font color='".$notmember[0]->groupFontColor."' style='font-weight: ".$notmember[0]->groupFontWeight."'>".$notmember[0]->groupName."</font> ";
                  echo "</td></tr>";
                }
              }else{
                echo "<th style='background-color: #EEE'>Groups NOT Allowed to Moderate Blog: </th>";
                echo "<tr><td> None </td></tr>";
              }
            echo "</table>";
          ?>

    	</div>
    </div>

    <!-- Start of Blog Admin groups -->
    <div class='col-lg-4 col-md-4'>
    	<div class='card mb-3 border rounded mb-4 shadow-sm p-2'>
    		<div class='card-header h4'>
    			Blog Administrator Group
          <?php echo PageFunctions::displayPopover('Blog Administrator Group', 'Sets which Member Groups can Administrate the Blog.', false, 'btn btn-sm btn-light float-right'); ?>
    		</div>

    			<?php
            echo "<table class='table table-hover responsive'>";
              // Displays User's Groups they are a member of
              if(!empty($data['b_admins_member_groups'])){
                echo "<th style='background-color: #EEE'>Groups Allowed to Admin Blog: </th>";
                foreach($data['b_admins_member_groups'] as $member){
                  echo "<tr><td>";
                  echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                  echo "<input type='hidden' name='token_blog' value='".$data['csrf_token']."'>";
                  echo "<input type='hidden' name='remove_group_admin' value='true' />";
                  echo "<input type='hidden' name='groupID' value='".$member[0]->groupID."'>";
                  echo "<button class='btn btn-sm btn-danger' name='submit' type='submit'>Remove</button>";
                  echo Form::close();
                  echo " - <font color='".$member[0]->groupFontColor."' style='font-weight: ".$member[0]->groupFontWeight."'>".$member[0]->groupName."</font>";
                  echo "</td></tr>";
                }
              }else{
                echo "<th style='background-color: #EEE'>Groups Allowed to Admin Blog: </th>";
                echo "<tr><td> None </td></tr>";
              }
            echo "</table>";

            echo "<table class='table table-hover responsive'>";
              // Displays User's Groups they are not a member of
              if(!empty($data['b_admins_notmember_groups'])){
                echo "<th style='background-color: #EEE'>Groups NOT Allowed to Admin Blog: </th>";
                foreach($data['b_admins_notmember_groups'] as $notmember){
                  echo "<tr><td>";
                  echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                  echo "<input type='hidden' name='token_blog' value='".$data['csrf_token']."'>";
                  echo "<input type='hidden' name='add_group_admin' value='true' />";
                  echo "<input type='hidden' name='groupID' value='".$notmember[0]->groupID."'>";
                  echo "<button class='btn btn-sm btn-success' name='submit' type='submit'>Add</button>";
                  echo Form::close();
                  echo " - <font color='".$notmember[0]->groupFontColor."' style='font-weight: ".$notmember[0]->groupFontWeight."'>".$notmember[0]->groupName."</font> ";
                  echo "</td></tr>";
                }
              }else{
                echo "<th style='background-color: #EEE'>Groups NOT Allowed to Admin Blog: </th>";
                echo "<tr><td> None </td></tr>";
              }
            echo "</table>";
          ?>

    	</div>
    </div>
  </div>

</div>
