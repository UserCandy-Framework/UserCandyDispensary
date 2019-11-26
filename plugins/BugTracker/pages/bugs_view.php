<?php
/**
* Demo Plugin Home Page
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version 1.0.0
*/

use Helpers\{BBCode,CurrentUserData,ErrorMessages};

/** Get data from BugTracker Function within BugTracker Class **/
$bug_output = $BugTrackerModel->getBug($get_var_2);

if (empty($bug_output))
  ErrorMessages::push('Error.  No data exist for that ID.  Try again.', 'BugTracker');

/** Collect Data page for view **/
$data['title'] = $bug_output[0]->summary;
$data['welcomeMessage'] = $bug_output[0]->summary;

/** Setup Breadcrumbs **/
$data['breadcrumbs'] = "<li class='breadcrumb-item'><a href='".SITE_URL."BugTracker'>BugTracker</a></li><li class='breadcrumb-item active'>View</li>";

/** Set Status button color based on status **/
$status = $bug_output[0]->status;
if($status == 'Open'){
	$status_button = "btn-info";
}else if($status == 'InProgress'){
	$status_button = "btn-primary";
}else if($status == 'Stalled'){
	$status_button = "btn-warning";
}else if($status == 'Resolved'){
	$status_button = "btn-secondary";
}else if($status == 'Closed'){
	$status_button = "btn-danger";
}else{
	$status_button = "btn-success";
}

/** Get Bug Type for display **/
$type = $bug_output[0]->type;
if($type == 'Bug'){
	$bug_type = 'Bug';
	$bug_type_des = 'Something is not working like it should.';
}else if($type == 'Doc'){
	$bug_type = 'Documentation';
	$bug_type_des = 'Improvements or additions to documentation.';
}else if($type == 'Dup'){
	$bug_type = 'Duplicate';
	$bug_type_des = 'This bug already exist in database.';
}else if($type == 'Enh'){
	$bug_type = 'Enhancement';
	$bug_type_des = 'New feature or request.';
}else if($type == 'Hel'){
	$bug_type = 'Help Wanted';
	$bug_type_des = 'Extra attention is requested.';
}else if($type == 'Inv'){
	$bug_type = 'Invalid';
	$bug_type_des = 'Bug does not look to be valid.';
}else if($type == 'Que'){
	$bug_type = 'Question';
	$bug_type_des = 'More information is needed.';
}else if($type == 'Won'){
	$bug_type = "Won't Fix";
	$bug_type_des = 'This bug will not be worked on.';
}

?>

<div class="col">
	<div class="card mb-3">
		<div class="card-header h4">
			<button type="button" class="btn btn-md <?=$status_button?>" disabled><?=$status?></button> <?=$data['title']?>
			<?php
				if($userModAdmin == true){
					echo "<a href='".SITE_URL."BugTracker/Edit/{$bug_output[0]->id}' class='btn btn-sm btn-primary float-right'><span class='fas fa-fw fa-edit'></span></a>";
				}
			?>
		</div>
		<div class="card-body">
			<strong>Created By</strong>
			<?php
				$user_image_display = CurrentUserData::getUserImage($bug_output[0]->creator_userID);
				$username = CurrentUserData::getUserName($bug_output[0]->creator_userID);
				$user_online = CurrentUserData::getUserStatusDot($bug_output[0]->creator_userID);
				echo "<img alt='{$username}'s Profile Picture' src='".SITE_URL.IMG_DIR_PROFILE.$user_image_display."' class='rounded img-fluid' style='height: 25px'> ";
				echo "$user_online<a href='".SITE_URL."Profile/{$username}'> {$username}</a>";
			?>
			@ <?=$bug_output[0]->created_date?>
			<hr>
			<?php if(!empty($bug_output[0]->modifi_userID)){ ?>
				<strong>Modified By</strong>
				<?php
					$mod_user_image_display = CurrentUserData::getUserImage($bug_output[0]->modifi_userID);
					$mod_username = CurrentUserData::getUserName($bug_output[0]->modifi_userID);
					$mod_user_online = CurrentUserData::getUserStatusDot($bug_output[0]->modifi_userID);
					echo "<img alt='{$mod_username}'s Profile Picture' src='".SITE_URL.IMG_DIR_PROFILE.$mod_user_image_display."' class='rounded img-fluid' style='height: 25px'> ";
					echo "$mod_user_online<a href='".SITE_URL."Profile/{$mod_username}'> {$mod_username}</a>";
				?>
				@ <?=$bug_output[0]->modified_date?>
				<hr>
			<?php } ?>
			<div class="row">
				<div class="col-8 forum">
					<strong>Description</strong>
					<?=BBCode::getHtml($bug_output[0]->description)?>
				</div>
				<div class="col-4 border-left">
					<strong>Package</strong><br>
					<?=$bug_output[0]->package?>
					<hr>
					<strong>Type</strong><br>
					<?=$bug_type?><Br>
					<p class='text-muted h6'><?=$bug_type_des?><p>
					<hr>
					<strong>Priority</strong><br>
					<?=$bug_output[0]->priority?>
					<hr>
					<strong>Version</strong><br>
					<?=$bug_output[0]->version?>
					<hr>
					<strong>Server</strong><br>
					<?=$bug_output[0]->server?>
					<hr>
					<strong>Folder</strong><br>
					<?=$bug_output[0]->folder?>
					<?php if(!empty($bug_output[0]->assigned_userID)){ ?>
						<hr>
						<strong>Assigned To</strong><Br>
						<?php
							$ass_user_image_display = CurrentUserData::getUserImage($bug_output[0]->assigned_userID);
							$ass_username = CurrentUserData::getUserName($bug_output[0]->assigned_userID);
							$ass_user_online = CurrentUserData::getUserStatusDot($bug_output[0]->assigned_userID);
							echo "<img alt='{$ass_username}'s Profile Picture' src='".SITE_URL.IMG_DIR_PROFILE.$ass_user_image_display."' class='rounded img-fluid' style='height: 25px'> ";
							echo "$ass_user_online<a href='".SITE_URL."Profile/{$ass_username}'> {$ass_username}</a>";
						?>
					<?php } ?>
				</div>
			</div>
		</div>
  </div>
<?php
  /** Check if Comments Helper is installed **/
  if($DispenserModel->checkDispenserEnabled('CommentsHelper')){
?>
	<div class="card mb-3">
		<div class="card-header h4">
			<?=CommentsHelper::getComments($get_var_2, 'BugTracker', '', '')?> Comments
		</div>
		<div class="card-body">
			<?=CommentsHelper::displayComments($get_var_2, 'BugTracker')?>
		</div>
	</div>
<?php } ?>
</div>
