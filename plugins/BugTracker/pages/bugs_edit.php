<?php
/**
* Demo Plugin Home Page
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version 1.0.0
*/

use Core\Language;
use Helpers\{PageFunctions,Form,BBCode,Csrf,Request,ErrorMessages,SuccessMessages,CurrentUserData};

/** If User Not logged in - kick them out **/
if (!$auth->isLogged())
  ErrorMessages::push(Language::show('user_not_logged_in', 'Auth'), 'BugTracker');

if (!$userModAdmin)
  ErrorMessages::push('You must be Mod or Admin to view that page.', 'BugTracker');

/** Get data from BugTracker Function within BugTracker Class **/
$bug_output = $BugTrackerModel->getBug($get_var_2);

if (empty($bug_output))
  ErrorMessages::push('Error.  No data exist for that ID.  Try again.', 'BugTracker');

/** Check to see if User is submiting form data */
if(isset($_POST['submit'])){
	  /** Check to make sure the csrf token is good */
	  if (Csrf::isTokenValid('BugTracker')) {
	      /** Check to make sure Admin is updating settings */
	      if(Request::post('update_bug') == "true"){
	          /** Get data sbmitted by form */
	          $summary = Request::post('summary');
	          $package = Request::post('package');
	          $version = Request::post('version');
						$server = Request::post('server');
	          $type = Request::post('type');
						$folder = Request::post('folder');
	          $description = Request::post('description');
            $status = Request::post('status');
            $priority = Request::post('priority');
            $assigned_userID = Request::post('assigned_userID');

	          /** Send new Bug to Database **/
	          if($new_id = $BugTrackerModel->editBug($get_var_2, $summary, $package, $version, $server, $type, $folder, $description, $currentUserData[0]->userID, $status, $priority, $assigned_userID)){
                $BugTrackerModel->updateBugEmail($get_var_2);
                /** Success */
	              SuccessMessages::push('You Have Successfully Updated a Bug in Database', 'BugTracker/View/'.$get_var_2);
	          }else{
	              /** Error Message Display */
	              ErrorMessages::push('Error Submitting Bug to Database', 'BugTracker');
	          }
	      }else{
	          /** Error Message Display */
	          ErrorMessages::push('Error Submitting Bug to Database', 'BugTracker');
	      }
	  }else{
	      /** Error Message Display */
	      ErrorMessages::push('Error Submitting Bug to Database', 'BugTracker');
	  }
}

/** Setup Token for Form */
$data['csrfToken'] = Csrf::makeToken('BugTracker');


/** Collect Data page for view **/
$data['title'] = "BugTracker Submit";
$data['welcomeMessage'] = "Welcome to the BugTracker Submit Page.  <br><br>Please fill out the form below with as much detail as possible.  Also include any code or error snippits avaialbe to help developers better troubleshoot the issue.";

/** Setup Breadcrumbs **/
$data['breadcrumbs'] = "<li class='breadcrumb-item'><a href='".SITE_URL."BugTracker'>BugTracker</a></li><li class='breadcrumb-item active'>Edit</li>";

?>

<div class='col'>
	<div class='card mb-3'>
		<div class='card-header h4'>
			<?php echo $data['title'];  ?>
      <?php echo PageFunctions::displayPopover('BugTracker', 'BugTracker is designed to help improve this project.  Make sure to be as descriptive as possible to insure quick resolution.', false, 'btn btn-sm btn-light'); ?>
		</div>
		<div class='card-body'>
			<p><?php echo $data['welcomeMessage'] ?></p>

			<?php echo Form::open(array('method' => 'post')); ?>

			<!-- Bug Summary -->
      <div class='input-group mb-3' style='margin-bottom: 25px'>
    		<div class='input-group-prepend'>
    			<span class='input-group-text'>Summary</span>
    		</div>
        <?php echo Form::input(array('type' => 'text', 'name' => 'summary', 'class' => 'form-control', 'value' => $bug_output[0]->summary, 'placeholder' => 'Summary - Breif description of the bug.')); ?>
      </div>

			<!-- Bug Package -->
      <div class='input-group mb-3' style='margin-bottom: 25px'>
    		<div class='input-group-prepend'>
    			<span class='input-group-text'>Package</span>
    		</div>
        <?php echo Form::input(array('type' => 'text', 'name' => 'package', 'class' => 'form-control', 'value' => $bug_output[0]->package, 'placeholder' => 'Package - EX: UserCandy Framework, Demo Plugin, Demo Widget, AdminPanel, etc.')); ?>
      </div>

			<!-- Bug Version -->
      <div class='input-group mb-3' style='margin-bottom: 25px'>
    		<div class='input-group-prepend'>
    			<span class='input-group-text'>Version</span>
    		</div>
        <?php echo Form::input(array('type' => 'text', 'name' => 'version', 'class' => 'form-control', 'value' => $bug_output[0]->version, 'placeholder' => 'Version of current Package.  EX: 1.0.0, 2.1.0, 1.1.3')); ?>
      </div>

			<!-- Bug Server -->
      <div class='input-group mb-3' style='margin-bottom: 25px'>
    		<div class='input-group-prepend'>
    			<span class='input-group-text'>Server Information</span>
    		</div>
        <?php echo Form::input(array('type' => 'text', 'name' => 'server', 'class' => 'form-control', 'value' => $bug_output[0]->server, 'placeholder' => 'Server Information - What OS, Webserver Type, Versions, and other webserver information.')); ?>
      </div>

      <!-- Bug Type -->
      <div class='input-group mb-3' style='margin-bottom: 25px'>
        <div class="input-group-prepend">
          <span class='input-group-text'>Type</span>
        </div>
        <select class='form-control' id='type' name='type'>
          <option value='Bug' <?php if($bug_output[0]->type == 'Bug'){ echo "SELECTED"; } ?> />Bug - Something is not working like it should.</option>
					<option value='Doc' <?php if($bug_output[0]->type == 'Doc'){ echo "SELECTED"; } ?> />Documentation - Improvements or additions to documentation.</option>
					<option value='Dup' <?php if($bug_output[0]->type == 'Dup'){ echo "SELECTED"; } ?> />Duplicate - This bug already exist in database.</option>
					<option value='Enh' <?php if($bug_output[0]->type == 'Enh'){ echo "SELECTED"; } ?> />Enhancement - New feature or request.</option>
					<option value='Hel' <?php if($bug_output[0]->type == 'Hel'){ echo "SELECTED"; } ?> />Help Wanted - Extra attention is requested.</option>
					<option value='Inv' <?php if($bug_output[0]->type == 'Inv'){ echo "SELECTED"; } ?> />Invalid - Bug does not look to be valid.</option>
					<option value='Que' <?php if($bug_output[0]->type == 'Que'){ echo "SELECTED"; } ?> />Question - More information is needed.</option>
					<option value='Won' <?php if($bug_output[0]->type == 'Won'){ echo "SELECTED"; } ?> />Won't Fix - This Bug will not be worked on.</option>
        </select>
      </div>

			<!-- Bug Folder -->
      <div class='input-group mb-3' style='margin-bottom: 25px'>
    		<div class='input-group-prepend'>
    			<span class='input-group-text'>Folder Location</span>
    		</div>
        <?php echo Form::input(array('type' => 'text', 'name' => 'folder', 'class' => 'form-control', 'value' => $bug_output[0]->folder, 'placeholder' => 'Folder Location.  Where the file is located within the project that this bug is in.')); ?>
      </div>

      <!-- Bug Status -->
      <div class='input-group mb-3' style='margin-bottom: 25px'>
        <div class="input-group-prepend">
          <span class='input-group-text'>Status</span>
        </div>
        <select class='form-control' id='status' name='status'>
          <option value='New' <?php if($bug_output[0]->status == 'New'){ echo "SELECTED"; } ?> />New</option>
					<option value='Open' <?php if($bug_output[0]->status == 'Open'){ echo "SELECTED"; } ?> />Open</option>
					<option value='InProgress' <?php if($bug_output[0]->status == 'InProgress'){ echo "SELECTED"; } ?> />InProgress</option>
					<option value='Stalled' <?php if($bug_output[0]->status == 'Stalled'){ echo "SELECTED"; } ?> />Stalled</option>
					<option value='Resolved' <?php if($bug_output[0]->status == 'Resolved'){ echo "SELECTED"; } ?> />Resolved</option>
					<option value='Closed' <?php if($bug_output[0]->status == 'Closed'){ echo "SELECTED"; } ?> />Closed</option>
        </select>
      </div>

      <!-- Bug Priority -->
      <div class='input-group mb-3' style='margin-bottom: 25px'>
        <div class="input-group-prepend">
          <span class='input-group-text'>Priority</span>
        </div>
        <select class='form-control' id='priority' name='priority'>
          <option value='Low' <?php if($bug_output[0]->priority == 'Low'){ echo "SELECTED"; } ?> />Low</option>
					<option value='Medium' <?php if($bug_output[0]->priority == 'Medium'){ echo "SELECTED"; } ?> />Medium</option>
					<option value='High' <?php if($bug_output[0]->priority == 'High'){ echo "SELECTED"; } ?> />High</option>
        </select>
      </div>

      <!-- Assigned User -->
      <div class='input-group mb-3' style='margin-bottom: 25px'>
        <div class="input-group-prepend">
          <span class='input-group-text'>Assigned User</span>
        </div>
        <select class='form-control' id='assigned_userID' name='assigned_userID'>
          <?php
            $data['members'] = $membersModel->getMembers('UN-ASC');
            foreach ($data['members'] as $row) {
              echo "<option value='{$row->userID}' "; if($bug_output[0]->assigned_userID == $row->userID){ echo "SELECTED"; } echo "/>{$row->username} - {$row->groupName}</option>";
            }
          ?>
        </select>
      </div>

      <!-- BBCode Buttons -->
      <?=BBCode::displayButtons('description')?>

      <!-- Bug Description -->
      <div class='input-group mb-3' style='margin-bottom: 25px'>
        <div class='input-group-prepend'>
          <span class='input-group-text'>Description</span>
        </div>
        <?php echo Form::textBox(array('type' => 'text', 'name' => 'description', 'id' => 'description', 'class' => 'form-control', 'value' => $bug_output[0]->description, 'placeholder' => 'Bug Description', 'rows' => '10')); ?>
      </div>

			<button class="btn btn-md btn-success" name="submit" type="submit">
	        Update Bug
	    </button>
	    <!-- CSRF Token and What is Being Updated -->
	    <input type="hidden" name="token_BugTracker" value="<?php echo $data['csrfToken']; ?>" />
	    <input type="hidden" name="update_bug" value="true" />
	    <?php echo Form::close(); ?>


    </div>
	</div>
</div>
