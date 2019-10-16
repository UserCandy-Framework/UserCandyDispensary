<?php
/**
* UserApplePie v4 Forum View Plugin Admin Categories
*
* UserApplePie - Forum Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.2 for UAP v.4.3.0
*/

use Helpers\{ErrorMessages,SuccessMessages,Paginator,Csrf,Request,Url,PageFunctions,Form};
use Models\AdminPanelModel;

/** Forum Categories Admin Panel View **/
(empty($get_var_2)) ? $action = null : $action = $get_var_2;
(empty($get_var_3)) ? $id = null : $id = $get_var_3;
(empty($get_var_4)) ? $id2 = null : $id2 = $get_var_4;

// Get data for users
$data['current_page'] = $_SERVER['REQUEST_URI'];
$data['title'] = "Forum Categories";

// Check to see if there is an action
if($action != null && $id != null){
  // Check to see if action is edit
  if($action == 'CatMainEdit'){
    // Check to make sure admin is trying to update
    if(isset($_POST['submit'])){
      /* Check to see if site is a demo site */
      if(DEMO_SITE != 'TRUE'){
        // Check to make sure the csrf token is good
        if (Csrf::isTokenValid('ForumAdmin')) {
          if($_POST['action'] == "update_cat_main_title"){
            // Catch password inputs using the Request helper
            $new_forum_title = Request::post('forum_title');
            $prev_forum_title = Request::post('prev_forum_title');
            if($forum->updateCatMainTitle($prev_forum_title,$new_forum_title)){
              // Success
              SuccessMessages::push('You Have Successfully Updated Forum Main Category Title to <b>'.$new_forum_title.'</b>', 'AdminPanel-Forum/Categories');
            }else{
              /* Error Message Display */
              ErrorMessages::push('Updating Forum Main Category Title', 'AdminPanel-Forum/Categories');
            }
          }
        }
      }else{
        /* Error Message Display */
        ErrorMessages::push('Demo Limit - Forum Settings Disabled', 'AdminPanel-Forum/Categories');
      }
    }else{
      // Get data for CatMainEdit Form
      $data['edit_cat_main'] = true;
      $data['data_cat_main'] = $forum->getCatMain($id);

      $data['welcome_message'] = "You are about to Edit Selected Forum Main Category.";

      // Setup Breadcrumbs
      $data['breadcrumbs'] = "
        <li class='breadcrumb-item'><a href='".SITE_URL."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
        <li class='breadcrumb-item'><a href='".SITE_URL."AdminPanel-Forum/Categories'><i class='fa fa-fw fa-list'></i> ".$data['title']."</a></li>
        <li class='breadcrumb-item active'><i class='fas fa-fw fa-edit'></i> Edit Main Category</li>
      ";
    }
  }else if($action == "CatMainUp"){
    if($forum->moveUpCatMain($id)){
      // Success
      SuccessMessages::push('You Have Successfully Moved Up Forum Main Category', 'AdminPanel-Forum/Categories');
    }else{
      /* Error Message Display */
      ErrorMessages::push('Move Up Forum Main Category Failed', 'AdminPanel-Forum/Categories');
    }
  }else if($action == "CatMainDown"){
    if($forum->moveDownCatMain($id)){
      // Success
      SuccessMessages::push('You Have Successfully Moved Down Forum Main Category', 'AdminPanel-Forum/Categories');
    }else{
      /* Error Message Display */
      ErrorMessages::push('Move Down Forum Main Category Failed', 'AdminPanel-Forum/Categories');
    }
  }else if($action == 'CatMainNew'){
    // Check to make sure admin is trying to update
    if(isset($_POST['submit'])){
      /* Check to see if site is a demo site */
      if(DEMO_SITE != 'TRUE'){
        // Check to make sure the csrf token is good
        if (Csrf::isTokenValid('ForumAdmin')) {
          // Add new cate main title to database
          if($_POST['action'] == "new_cat_main_title"){
            // Catch inputs using the Request helper
            $forum_title = Request::post('forum_title');
            // Get last order title number from db
            $last_order_num = $forum->getLastCatMain();
            // Attempt to add new Main Category Title to DB
            if($forum->newCatMainTitle($forum_title,'forum',$last_order_num)){
              // Success
              SuccessMessages::push('You Have Successfully Created New Forum Main Category Title <b>'.$new_forum_title.'</b>', 'AdminPanel-Forum/Categories');
            }else{
              /* Error Message Display */
              ErrorMessages::push('New Forum Main Category Failed', 'AdminPanel-Forum/Categories');
            }
          }
        }
      }else{
        /* Error Message Display */
        ErrorMessages::push('Demo Limit - Forum Settings Disabled', 'AdminPanel-Forum/Categories');
      }
    }
  }else if($action == "CatSubList"){
    // Check to make sure admin is trying to update
    if(isset($_POST['submit'])){
      /* Check to see if site is a demo site */
      if(DEMO_SITE != 'TRUE'){
        // Check to make sure the csrf token is good
        if (Csrf::isTokenValid('ForumAdmin')) {
          // Add new cate main title to database
          if($_POST['action'] == "new_cat_sub"){
            // Catch inputs using the Request helper
            $forum_title = Request::post('forum_title');
            $forum_cat = Request::post('forum_cat');
            $forum_des = Request::post('forum_des');
            // Check to see if we are adding to a new main cat
            if($forum->checkSubCat($forum_title)){
              // Get last cat sub order id
              $last_cat_order_id = $forum->getLastCatSub($forum_title);
              // Get forum order title id
              $forum_order_title = $forum->getForumOrderTitle($forum_title);
              // Run insert for new sub cat
              $run_sub_cat = $forum->newSubCat($forum_title,$forum_cat,$forum_des,$last_cat_order_id,$forum_order_title);
            }else{
              // Run update for new main cat
              $run_sub_cat = $forum->updateSubCat($id,$forum_cat,$forum_des);
            }
            // Attempt to update/insert sub cat in db
            if($run_sub_cat){
              // Success
              SuccessMessages::push('You Have Successfully Created Forum Sub Category', 'AdminPanel-Forum/Categories/CatSubList/'.$id);
            }else{
              /* Error Message Display */
              ErrorMessages::push('Create Forum Sub Category Failed', 'AdminPanel-Forum/Categories');
            }
          }
        }
      }else{
        /* Error Message Display */
        ErrorMessages::push('Demo Limit - Forum Settings Disabled', 'AdminPanel-Forum/Categories');
      }
    }else{
      // Set goods for Forum Sub Categories Listing
      $data['cat_sub_list'] = true;
      $data['cat_main_title'] = $forum->getCatMain($id);
      $data['cat_sub_titles'] = $forum->getCatSubs($data['cat_main_title']);
      $data['fourm_cat_sub_last'] = $forum->getLastCatSub($data['cat_main_title']);

      $data['welcome_message'] = "You are viewing a complete list of sub categories for requeted main category.";

      // Setup Breadcrumbs
      $data['breadcrumbs'] = "
        <li class='breadcrumb-item'><a href='".SITE_URL."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
        <li class='breadcrumb-item'><a href='".SITE_URL."AdminPanel-Forum/Categories'><i class='fa fa-fw fa-list'></i> ".$data['title']."</a></li>
        <li class='breadcrumb-item active'><i class='fas fa-fw fa-edit'></i> Sub Categories List</li>
      ";
    }
  }else if($action == "CatSubEdit"){
    // Check to make sure admin is trying to update
    if(isset($_POST['submit'])){
      /* Check to see if site is a demo site */
      if(DEMO_SITE != 'TRUE'){
        // Check to make sure the csrf token is good
        if (Csrf::isTokenValid('ForumAdmin')) {
          // Add new cate main title to database
          if($_POST['action'] == "edit_cat_sub"){
            // Catch inputs using the Request helper
            $forum_cat = Request::post('forum_cat');
            $forum_des = Request::post('forum_des');
            // Attempt to update sub cat in db
            if($forum->updateSubCat($id,$forum_cat,$forum_des)){
              // Success
              SuccessMessages::push('You Have Successfully Updated Forum Sub Category', 'AdminPanel-Forum/Categories/CatSubList/'.$id);
            }else{
              /* Error Message Display */
              ErrorMessages::push('Update Forum Sub Category Failed', 'AdminPanel-Forum/Categories');
            }
          }
        }
      }else{
        /* Error Message Display */
        ErrorMessages::push('Demo Limit - Forum Settings Disabled', 'AdminPanel-Forum/Categories');
      }
    }else{
      // Display Edit Forum for Selected Sub Cat
      $data['cat_sub_edit'] = true;
      $data['cat_sub_data'] = $forum->getCatSubData($id);

      $data['welcome_message'] = "You are about to edit requeted sub category.";

      // Setup Breadcrumbs
      $data['breadcrumbs'] = "
        <li class='breadcrumb-item'><a href='".SITE_URL."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
        <li class='breadcrumb-item'><a href='".SITE_URL."AdminPanel-Forum/Categories'><i class='fa fa-fw fa-list'></i> ".$data['title']."</a></li>
        <li class='breadcrumb-item'><a href='".SITE_URL."AdminPanel-Forum/Categories/CatSubList/$id'><i class='fa fa-fw fa-list'></i> Sub Categories List</a></li>
        <li class='breadcrumb-item active'><i class='fas fa-fw fa-edit'></i> Edit Sub Category</li>
      ";
    }
  }else if($action == "DeleteSubCat"){
    // Check to make sure admin is trying to update
    if(isset($_POST['submit'])){
      /* Check to see if site is a demo site */
      if(DEMO_SITE != 'TRUE'){
        // Check to make sure the csrf token is good
        if (Csrf::isTokenValid('ForumAdmin')) {
          // Add new cate main title to database
          if($_POST['action'] == "delete_cat_sub"){
            // Catch inputs using the Request helper
            $delete_cat_sub_action = Request::post('delete_cat_sub_action');

            // Get title basted on forum_id
            $forum_title = $forum->getCatMain($id);

            // Get title basted on forum_cat
            $forum_cat = $forum->getCatSub($id);

            // Check to see what delete function admin has selected
            if($delete_cat_sub_action == "delete_all"){
              // Admin wants to delete Sub Cat and Everything Within it
              // First we delete all related topic Replies
              if($forum->deleteTopicsForumID($id)){
                $success_count = $success_count + 1;
              }
              // Second we delete all topics
              if($forum->deleteTopicRepliesForumID($id)){
                $success_count = $success_count + 1;
              }
              // Finally we delete the main cat and all related sub cats
              if($forum->deleteCatForumID($id)){
                $success_count = $success_count + 1;
              }
              // Check to see if everything was deleted Successfully
              if($success_count > 0){
                // Success
                SuccessMessages::push('You Have Successfully Deleted Sub Category: <b>'.$forum_title.' > '.$forum_cat.'</b> and Everything Within it!', 'AdminPanel-Forum/Categories');
              }
            }else{
              // Extract forum_id from move_to_# string
              $forum_id = str_replace("move_to_", "", $delete_cat_sub_action);
              if(!empty($forum_id)){
                // First Update Topic Replies forum_id
                if($forum->updateTopicRepliesForumID($id, $forum_id)){
                  $success_count = $success_count + 1;
                }
                // Second Update Topics forum_id
                if($forum->updateTopicsForumID($id, $forum_id)){
                  $success_count = $success_count + 1;
                }
                // Last delete the sub Category
                if($forum->deleteCatForumID($id)){
                  $success_count = $success_count + 1;
                }
                // Check to see if anything was done
                if($success_count > 0){
                  // Success
                  SuccessMessages::push('You Have Successfully Moved Main Category From <b>'.$old_forum_title.'</b> to <b>'.$new_forum_title.'</b>', 'AdminPanel-Forum/Categories/CatSubList/'.$forum_id);
                }
              }else{
                // User has not selected to delete or move main cat
                ErrorMessages::push('No Action Selected.  No actions executed.', 'AdminPanel-Forum/Categories/DeleteSubCat/'.$id);
              }
            }

          }
        }
      }else{
        /* Error Message Display */
        ErrorMessages::push('Demo Limit - Forum Settings Disabled', 'AdminPanel-Forum/Categories');
      }
    }else{
      $data['welcome_message'] = "You are about to delete requested sub category.  Please proceed with caution.";
      // Display Delete Cat Sub Form
      $data['delete_cat_sub'] = true;

      // Get list of all sub cats except current
      $data['list_all_cat_sub'] = $forum->catSubListExceptSel($id);
      $data['delete_cat_sub_title'] = $forum->getCatSub($id);

      // Setup Breadcrumbs
      $data['breadcrumbs'] = "
        <li class='breadcrumb-item'><a href='".SITE_URL."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
        <li class='breadcrumb-item'><a href='".SITE_URL."AdminPanel-Forum/Categories'><i class='fa fa-fw fa-list'></i> ".$data['title']."</a></li>
        <li class='breadcrumb-item'><a href='".SITE_URL."AdminPanel-Forum/Categories/CatSubList/".$id."'><i class='fa fa-fw fa-list'></i> Sub Categories List</a></li>
        <li class='breadcrumb-item active'><i class='fas fa-fw fa-edit'></i> Delete Sub Category</li>
      ";
    }
  }else if($action == "CatSubUp"){
    // Get forum_title for cat
    $data['cat_main_title'] = $forum->getCatMain($id);
    // Try to move up
    if($forum->moveUpCatSub($data['cat_main_title'],$id2)){
      // Success
      SuccessMessages::push('You Have Successfully Moved Up Forum Sub Category', 'AdminPanel-Forum/Categories/CatSubList/'.$id);
    }else{
      /* Error Message Display */
      ErrorMessages::push('Move Up Forum Main Category Failed', 'AdminPanel-Forum/Categories');
    }
  }else if($action == "CatSubDown"){
    // Get forum_title for cat
    $data['cat_main_title'] = $forum->getCatMain($id);
    // Try to move down
    if($forum->moveDownCatSub($data['cat_main_title'],$id2)){
      // Success
      SuccessMessages::push('You Have Successfully Moved Down Forum Sub Category', 'AdminPanel-Forum/Categories/CatSubList/'.$id);
    }else{
      /* Error Message Display */
      ErrorMessages::push('Move Down Forum Main Category Failed', 'AdminPanel-Forum/Categories');
    }
  }else if($action == "DeleteMainCat"){
    // Check to make sure admin is trying to update
    if(isset($_POST['submit'])){
      /* Check to see if site is a demo site */
      if(DEMO_SITE != 'TRUE'){
        // Check to make sure the csrf token is good
        if (Csrf::isTokenValid('ForumAdmin')) {
          // Add new cate main title to database
          if($_POST['action'] == "delete_cat_main"){
            // Catch inputs using the Request helper
            $delete_cat_main_action = Request::post('delete_cat_main_action');

            // Get title basted on forum_id
            $forum_title = $forum->getCatMain($id);

            // Check to see what delete function admin has selected
            if($delete_cat_main_action == "delete_all"){
              // Admin wants to delete Main Cat and Everything Within it
              // Get list of all forum_id's for this Main Cat
              $forum_id_all = $forum->getAllForumTitleIDs($forum_title);
              $success_count = "0";
              if(isset($forum_id_all)){
                foreach ($forum_id_all as $row) {
                  // First we delete all related topic Replies
                  if($forum->deleteTopicsForumID($row->forum_id)){
                    $success_count = $success_count + 1;
                  }
                  // Second we delete all topics
                  if($forum->deleteTopicRepliesForumID($row->forum_id)){
                    $success_count = $success_count + 1;
                  }
                  // Finally we delete the main cat and all related sub cats
                  if($forum->deleteCatForumID($row->forum_id)){
                    $success_count = $success_count + 1;
                  }
                }
              }
              if($success_count > 0){
                // Success
                SuccessMessages::push('You Have Successfully Deleted Main Category: <b>'.$forum_title.'</b> and Everything Within it!', 'AdminPanel-Forum/Categories');
              }
            }else{
              // Extract forum_id from move_to_# string
              $forum_id = str_replace("move_to_", "", $delete_cat_main_action);
              if(!empty($forum_id)){
                // Get new and old forum titles
                $new_forum_title = $forum->getCatMain($forum_id);
                $old_forum_title = $forum->getCatMain($id);
                // Get forum_order_title id for forum_title we are moving to
                $new_forum_order_title = $forum->getForumOrderTitle($new_forum_title);
                // Get last order id for new forum_title we are moving to
                $new_forum_order_cat = $forum->getLastCatSub($new_forum_title);
                // Update with the new forum title from the old one
                if($forum->moveForumSubCat($old_forum_title,$new_forum_title,$new_forum_order_title,$new_forum_order_cat)){
                  // Success
                  SuccessMessages::push('You Have Successfully Moved Main Category From <b>'.$old_forum_title.'</b> to <b>'.$new_forum_title.'</b>', 'AdminPanel-Forum/Categories/CatSubList/'.$forum_id);
                }
              }else{
                // User has not selected to delete or move main cat
                ErrorMessages::push('No Action Selected.  No actions executed.', 'AdminPanel-Forum/Categories/DeleteMainCat/'.$id);
              }
            }

          }
        }
      }else{
        /* Error Message Display */
        ErrorMessages::push('Demo Limit - Forum Settings Disabled', 'AdminPanel-Forum/Categories');
      }
    }else{
      // Show delete options for main cat
      $data['delete_cat_main'] = true;
      $data['welcome_message'] = "You are about to delete requested main category.  Please proceed with caution.";
      // Get title for main cat admin is about to delete
      $data['delete_cat_main_title'] = $forum->getCatMain($id);
      // Get all other main cat titles
      $data['list_all_cat_main'] = $forum->catMainListExceptSel($data['delete_cat_main_title']);

      // Setup Breadcrumbs
      $data['breadcrumbs'] = "
        <li class='breadcrumb-item'><a href='".SITE_URL."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
        <li class='breadcrumb-item'><a href='".SITE_URL."AdminPanel-Forum/Categories'><i class='fa fa-fw fa-list'></i> ".$data['title']."</a></li>
        <li class='breadcrumb-item active'><i class='fas fa-fw fa-edit'></i> Delete Main Category</li>
      ";
    }
  }
}else{
  // Get data for main categories
  $data['cat_main'] = $forum->catMainList();

  // Welcome message
  $data['welcome_message'] = "You are viewing a complete list of main categories.";

  // Setup Breadcrumbs
  $data['breadcrumbs'] = "
    <li class='breadcrumb-item'><a href='".SITE_URL."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
    <li class='breadcrumb-item active'><i class='fa fa-fw fa-list'></i> ".$data['title']."</li>
  ";
}

// Clean up for errors messages
if(!isset($data['edit_cat_main'])){ $data['edit_cat_main'] = ""; }
if(!isset($data['cat_sub_list'])){ $data['cat_sub_list'] = ""; }
if(!isset($data['cat_sub_edit'])){ $data['cat_sub_edit'] = ""; }
if(!isset($data['delete_cat_main'])){ $data['delete_cat_main'] = ""; }
if(!isset($data['delete_cat_sub'])){ $data['delete_cat_sub'] = ""; }

// Get Last main cat order number
$data['fourm_cat_main_last'] = $forum->getLastCatMain();

// Setup CSRF token
$data['csrf_token'] = Csrf::makeToken('ForumAdmin');

?>

<div class='col-lg-12 col-md-12 col-sm-12'>
	<div class='card mb-3'>
		<div class='card-header h4'>
			<?php echo $data['title'];  ?>
      <?php echo PageFunctions::displayPopover('Froum Categories', 'Forum Categories can be edited, or removed to fit the site needs.', false, 'btn btn-sm btn-light'); ?>
		</div>
		<div class='card-body'>
			<p><?php echo $data['welcome_message'] ?></p>
    </div>
	</div>

      <?php
            // Check to see if admin is editing a category
            if($data['edit_cat_main'] == true){
              // Display form with data to edit
              if(isset($data['data_cat_main'])){
                echo Form::open(array('method' => 'post'));
                  echo "<div class='card border-primary mb-3'>";
                    echo "<div class='card-header h4'>";
                      echo "<i class='fa fa-fw fa-list'></i> Update Main Category Title";
                      echo PageFunctions::displayPopover('Froum Category Title', 'Forum Category Title edit.  This updates the title of the selected category.', false, 'btn btn-sm btn-light');
                    echo "</div>";
                    echo "<div class='card-body'>";
                      echo Form::input(array('type' => 'hidden', 'name' => 'prev_forum_title', 'value' => $data['data_cat_main']));
                      echo Form::input(array('type' => 'hidden', 'name' => 'action', 'value' => 'update_cat_main_title'));
                      echo Form::input(array('type' => 'hidden', 'name' => 'token_ForumAdmin', 'value' => $data['csrf_token']));
                      echo Form::input(array('type' => 'text', 'name' => 'forum_title', 'class' => 'form-input text form-control', 'aria-describedby' => 'basic-addon1', 'value' => $data['data_cat_main'], 'placeholder' => 'Main Category Title', 'maxlength' => '100'));
                    echo "</div>";
                    echo "<div class='card-footer text-muted'>";
                      echo "<button class='btn btn-success' name='submit' type='submit'>Update Main Category Title</button>";
                    echo "</div>";
                  echo "</div>";
                echo Form::close();
              }
            }else if($data['cat_sub_list'] == true){
              // Display Main Category Title
              if(isset($data['cat_main_title'])){
                echo "<div class='well'><h4>Sub Categories for ".$data['cat_main_title']."</h4></div>";
              }
              // Display sub categories for requeted main category
              if(isset($data['cat_sub_titles'])){
                foreach ($data['cat_sub_titles'] as $row) {
                  if(!empty($row->forum_cat)){
                    echo "<div class='card border-primary mb-3'>";
                      echo "<div class='card-header h4'>";
                        echo "<strong>$row->forum_cat</strong><br>";
                        echo "$row->forum_des";
                      echo "</div>";
                      echo "<div class='card-body'>";
                        echo "<div class='col-lg-6 col-md-6'>";
                          // Display total number of topics for this category
                          echo " <div class='badge badge-warning' style='margin-top: 5px'>";
                          echo "Topics <span class='badge badge-light'>$row->total_topics_display</span>";
                          echo "</div> ";
                          // Display total number of topic replies for this category
                          echo " <div class='badge badge-warning' style='margin-top: 5px'>";
                          echo "Replies <span class='badge badge-light'>$row->total_topic_replys_display</span>";
                          echo "</div> ";
                        echo "</div>";
                        echo "<div class='col-lg-6 col-md-6' style='text-align: right'>";
                          // Check to see if object is at top
                          if($row->forum_order_cat > 1){
                            echo "<a href='".SITE_URL."AdminPanel-Forum/Categories/CatSubUp/$row->forum_id/$row->forum_order_cat' class='btn btn-primary btn-sm' role='button'><span class='fa fa-fw fa-caret-up' aria-hidden='true'></span></a> ";
                          }
                          // Check to see if object is at bottom
                          if($data['fourm_cat_sub_last'] != $row->forum_order_cat){
                            echo "<a href='".SITE_URL."AdminPanel-Forum/Categories/CatSubDown/$row->forum_id/$row->forum_order_cat' class='btn btn-primary btn-sm' role='button'><span class='fa fa-fw fa-caret-down' aria-hidden='true'></span></a> ";
                          }
                          echo "<a href='".SITE_URL."AdminPanel-Forum/Categories/CatSubEdit/$row->forum_id' class='btn btn-success btn-sm' role='button'><span class='fa fa-fw fa-cog' aria-hidden='true'></span> Edit</a> ";
                          echo "<a href='".SITE_URL."AdminPanel-Forum/Categories/DeleteSubCat/$row->forum_id' class='btn btn-danger btn-sm' role='button'><span class='fa fa-fw fa-trash' aria-hidden='true'></span></a> ";
                        echo "</div>";
                      echo "</div>";
                    echo "</div>";
                  }else{
                    echo "<div class='well'>No Sub categories for ".$data['cat_main_title']."</div>";
                  }
                }
              }
              // Display form to create new sub cat
              echo Form::open(array('method' => 'post', 'action' => SITE_URL.'AdminPanel-Forum/Categories/CatSubList/'.$row->forum_id));
                echo "<div class='card border-info mb-3'>";
                  echo "<div class='card-header h4'>";
                    echo "<i class='fa fa-fw fa-list'></i> Create New Sub Category";
                    echo PageFunctions::displayPopover('Create New Sub Category', 'Create a new sub category for the selected forum category.', false, 'btn btn-sm btn-light');
                  echo "</div>";
                  echo "<div class='card-body'>";
                    echo Form::input(array('type' => 'hidden', 'name' => 'action', 'value' => 'new_cat_sub'));
                    echo Form::input(array('type' => 'hidden', 'name' => 'forum_title', 'value' => $data['cat_main_title']));
                    echo Form::input(array('type' => 'hidden', 'name' => 'token_ForumAdmin', 'value' => $data['csrf_token']));
                    echo "<strong>Sub Cat Title:</strong>";
                    echo Form::input(array('type' => 'text', 'name' => 'forum_cat', 'class' => 'form-input text form-control', 'aria-describedby' => 'basic-addon1', 'placeholder' => 'New Sub Category Title', 'maxlength' => '255'));
                    echo "<strong>Sub Cat Description:</strong>";
                    echo Form::textBox(array('type' => 'text', 'name' => 'forum_des', 'class' => 'form-input text form-control', 'aria-describedby' => 'basic-addon1', 'placeholder' => 'New Sub Category Description'));
                  echo "</div>";
                  echo "<div class='card-footer text-muted'>";
                    echo "<button class='btn btn-success' name='submit' type='submit'>Create New Sub Category</button>";
                  echo "</div>";
                echo "</div>";
              echo Form::close();
            }else if($data['cat_sub_edit'] == true){
              // Display Cat Sub Edit Form
              if(isset($data['cat_sub_data'])){
                foreach ($data['cat_sub_data'] as $row) {
                  echo Form::open(array('method' => 'post', 'action' => SITE_URL.'AdminPanel-Forum/Categories/CatSubEdit/'.$row->forum_id));
                    echo "<div class='card border-primary mb-3'>";
                      echo "<div class='card-header h4'>";
                        echo "<i class='fa fa-fw fa-list'></i> Edit Sub Category";
                        echo PageFunctions::displayPopover('Edit Sub Category', 'Edit sub category for the selected forum category.', false, 'btn btn-sm btn-light');
                      echo "</div>";
                      echo "<div class='card-body'>";
                        echo Form::input(array('type' => 'hidden', 'name' => 'action', 'value' => 'edit_cat_sub'));
                        echo Form::input(array('type' => 'hidden', 'name' => 'token_ForumAdmin', 'value' => $data['csrf_token']));
                        echo "<strong>Sub Cat Title:</strong>";
                        echo Form::input(array('type' => 'text', 'name' => 'forum_cat', 'value' => $row->forum_cat, 'class' => 'form-input text form-control', 'aria-describedby' => 'basic-addon1', 'placeholder' => 'Sub Category Title', 'maxlength' => '255'));
                        echo "<strong>Sub Cat Description:</strong>";
                        echo Form::textBox(array('type' => 'text', 'name' => 'forum_des', 'value' => $row->forum_des, 'class' => 'form-input text form-control', 'aria-describedby' => 'basic-addon1', 'placeholder' => 'Sub Category Description'));
                      echo "</div>";
                      echo "<div class='card-footer text-muted'>";
                        echo "<button class='btn btn-success' name='submit' type='submit'>Update Sub Category</button>";
                      echo "</div>";
                    echo "</div>";
                  echo Form::close();
                }
              }
            }else if($data['delete_cat_main']  == true){
              // Display Delete Main Cat Form
              echo Form::open(array('method' => 'post'));
                echo "<div class='card card-danger'>";
                  echo "<div class='card-header h4'>";
                    echo "Delete Forum Main Category: <strong>".$data['delete_cat_main_title']."</strong>";
                    echo PageFunctions::displayPopover('Delete Forum Main Category', 'Delete the selected forum category. CAN NOT BE UNDONE!', false, 'btn btn-sm btn-light');
                  echo "</div>";
                  echo "<div class='card-body'>";
                    echo "What would you like to do with all Sub Categories, Topics, and Topic Replies that are connected to Main Category: <strong>".$data['delete_cat_main_title']."</strong> ?";
                    echo "<select class='form-control' id='delete_cat_main_action' name='delete_cat_main_action'>";
                      echo "<option value=''>Select What To Do With Content</option>";
                      echo "<option value='delete_all'>Delete Everything Related to Main Category (This Can NOT be undone!)</option>";
                      // Show list of all main categories that admin can move content to
                      if(isset($data['list_all_cat_main'])){
                        foreach ($data['list_all_cat_main'] as $row) {
                          echo "<option value='move_to_$row->forum_id'>Move Content to: $row->forum_title</option>";
                        }
                      }
                    echo "</select>";
                  echo "</div>";
                  echo "<div class='card-footer text-muted'>";
                    echo "<button class='btn btn-danger' name='submit' type='submit'>Delete Main Category</button>";
                  echo "</div>";
                echo "</div>";
                echo Form::input(array('type' => 'hidden', 'name' => 'action', 'value' => 'delete_cat_main'));
                echo Form::input(array('type' => 'hidden', 'name' => 'token_ForumAdmin', 'value' => $data['csrf_token']));
              echo Form::close();
            }else if($data['delete_cat_sub']  == true){
              // Display Delete Sub Cat Form
              echo Form::open(array('method' => 'post'));
                echo "<div class='card card-danger'>";
                  echo "<div class='card-header h4'>";
                    echo "Delete Forum Sub Category: <strong>".$data['delete_cat_sub_title']."</strong>";
                    echo PageFunctions::displayPopover('Delete Sub Category', 'Delete selected sub category from the selected forum category. CAN NOT BE UNDONE!', false, 'btn btn-sm btn-light');
                  echo "</div>";
                  echo "<div class='card-body'>";
                    echo "What would you like to do with all Topics, and Topic Replies that are connected to Sub Category: <strong>".$data['delete_cat_sub_title']."</strong> ?";
                    echo "<select class='form-control' id='delete_cat_sub_action' name='delete_cat_sub_action'>";
                      echo "<option value=''>Select What To Do With Content</option>";
                      echo "<option value='delete_all'>Delete Everything Related to Sub Category (This Can NOT be undone!)</option>";
                      // Show list of all main categories that admin can move content to
                      if(isset($data['list_all_cat_sub'])){
                        foreach ($data['list_all_cat_sub'] as $row) {
                          echo "<option value='move_to_$row->forum_id'>Move Content to: $row->forum_title > $row->forum_cat</option>";
                        }
                      }
                    echo "</select>";
                  echo "</div>";
                  echo "<div class='card-footer text-muted'>";
                    echo "<button class='btn btn-danger' name='submit' type='submit'>Delete Sub Category</button>";
                  echo "</div>";
                echo "</div>";
                echo Form::input(array('type' => 'hidden', 'name' => 'action', 'value' => 'delete_cat_sub'));
                echo Form::input(array('type' => 'hidden', 'name' => 'token_ForumAdmin', 'value' => $data['csrf_token']));
              echo Form::close();
            }else{
              // Display main categories for forum
              if(isset($data['cat_main'])){
                foreach($data['cat_main'] as $row){
                  echo "<div class='card border-primary mb-3'>";
                    echo "<div class='card-header h4'>";
                        echo $row->forum_title;
                    echo "</div>";
                    echo "<div class='card-body'>";
                      echo "<div class='col-lg-6 col-md-6' style='text-align: left; margin-bottom: 2px'>";
                        // Display total number of sub cats for this category
                        echo " <div class='badge badge-warning' style='margin-top: 5px'>";
                        echo "Sub Cats <span class='badge badge-light'>$row->total_sub_cats</span>";
                        echo "</div> ";
                        // Display total number of topics for this category
                        echo " <div class='badge badge-warning' style='margin-top: 5px'>";
                        echo "Topics <span class='badge badge-light'>$row->total_topics_display</span>";
                        echo "</div> ";
                        // Display total number of topic replies for this category
                        echo " <div class='badge badge-warning' style='margin-top: 5px'>";
                        echo "Replies <span class='badge badge-light'>$row->total_topic_replys_display</span>";
                        echo "</div> ";
                      echo "</div>";
                      echo "<div class='col-lg-6 col-md-6' style='text-align: right; margin-bottom: 2px'>";
                        // Check to see if object is at top
                        if($row->forum_order_title > 1){
                          echo "<a href='".SITE_URL."AdminPanel-Forum/Categories/CatMainUp/$row->forum_order_title' class='btn btn-primary btn-sm' role='button'><span class='fa fa-fw fa-caret-up' aria-hidden='true'></span></a> ";
                        }
                        // Check to see if object is at bottom
                        if($data['fourm_cat_main_last'] != $row->forum_order_title){
                          echo "<a href='".SITE_URL."AdminPanel-Forum/Categories/CatMainDown/$row->forum_order_title' class='btn btn-primary btn-sm' role='button'><span class='fa fa-fw fa-caret-down' aria-hidden='true'></span></a> ";
                        }
                        echo "<a href='".SITE_URL."AdminPanel-Forum/Categories/CatMainEdit/$row->forum_id' class='btn btn-success btn-sm' role='button'><span class='fa fa-fw fa-cog' aria-hidden='true'></span> Edit</a> ";
                        echo "<a href='".SITE_URL."AdminPanel-Forum/Categories/CatSubList/$row->forum_id' class='btn btn-info btn-sm' role='button'><span class='fa fa-fw fa-list' aria-hidden='true'></span> Sub Categories</a> ";
                        echo "<a href='".SITE_URL."AdminPanel-Forum/Categories/DeleteMainCat/$row->forum_id' class='btn btn-danger btn-sm' role='button'><span class='fa fa-fw fa-trash' aria-hidden='true'></span></a> ";
                      echo "</div>";
                    echo "</div>";
                  echo "</div>";
                }// End of foreach
              }// End of isset
              // Display form to create new Main Category
              echo Form::open(array('method' => 'post', 'action' => SITE_URL.'AdminPanel-Forum/Categories/CatMainNew/1'));
                echo "<div class='card mb-3'>";
                  echo "<div class='card-header h4'>";
                    echo "<i class='fa fa-fw fa-list'></i> New Main Category Title";
                    echo PageFunctions::displayPopover('New Forum Category', 'New Forum Category can be added to the site forum.', false, 'btn btn-sm btn-light');
                  echo "</div>";
                  echo "<div class='card-body'>";
                    echo Form::input(array('type' => 'hidden', 'name' => 'action', 'value' => 'new_cat_main_title'));
                    echo Form::input(array('type' => 'hidden', 'name' => 'token_ForumAdmin', 'value' => $data['csrf_token']));
                    echo Form::input(array('type' => 'text', 'name' => 'forum_title', 'class' => 'form-input text form-control', 'aria-describedby' => 'basic-addon1', 'placeholder' => 'New Main Category Title', 'maxlength' => '100'));
                  echo "</div>";
                  echo "<div class='card-footer text-muted'>";
                    echo "<button class='btn btn-success' name='submit' type='submit'>Create New Main Category Title</button>";
                  echo "</div>";
                echo "</div>";
              echo Form::close();
            }// End of action check



      ?>
</div>
