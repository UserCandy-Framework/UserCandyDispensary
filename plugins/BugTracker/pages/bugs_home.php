<?php
/**
* Demo Plugin Home Page
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version 1.0.0
*/

use Helpers\{CurrentUserData,Paginator};

/** Setup Paginator **/
$bugs_limit = "30";
$pages = new Paginator($bugs_limit);  // How many rows per page
$current_page = $get_var_1;
$list_status = $get_var_2;
$total_num_bugs = $BugTrackerModel->getTotalBugs($list_status);
$pages->setTotal($total_num_bugs);
if(!empty($list_status)){ $link_search_bugs = "/".$list_status; }
$pageFormat = SITE_URL."BugTracker/"; // URL page where pages are
$data['pageLinks'] = $pages->pageLinks($pageFormat, $link_search_bugs, $current_page);
$data['current_page_num'] = $current_page;

/** Collect Data page for view **/
$data['title'] = "BugTracker";
$data['welcomeMessage'] = "Welcome to the BugTracker Home Page";

/** Setup Breadcrumbs **/
if(!empty($list_status)){
	$data['breadcrumbs'] = "<li class='breadcrumb-item'><a href='".SITE_URL."BugTracker'>BugTracker</a></li><li class='breadcrumb-item active'>$list_status</li>";
}else{
	$data['breadcrumbs'] = "<li class='breadcrumb-item active'>BugTracker</li>";
}

/** Get data from Demo Function within Demo Class **/
$bugs_output = $BugTrackerModel->getBugs($pages->getLimit($current_page, $bugs_limit), $list_status);

?>

<div class="col">
	<div class="card mb-3">
		<div class="card-header h4">
			<?=$data['title']?>
		</div>
		<div class="card-body">
			<?=$data['welcomeMessage']?>
		</div>
		<table class='table table-hover responsive'>
			<tr>
				<th>Bug ID</th>
				<th class='d-none d-md-table-cell'>Package</th>
				<th>Type</th>
				<th>Status</th>
				<th class='d-none d-md-table-cell'>Priority</th>
				<th class='d-none d-md-table-cell'>Summary</th>
				<?php
					/** Check if Comments Helper is installed **/
	        if($DispenserModel->checkDispenserEnabled('CommentsHelper')){
						echo "<th>Comments</th>";
					}
				?>
				<th class='d-none d-md-table-cell'>Submitted By</th>
			</tr>
			<?php
				if(isset($bugs_output)){
					foreach($bugs_output as $row) {
						/** Set Status button color based on status **/
						$status = $row->status;
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
						$type = $row->type;
						if($type == 'Bug'){
							$bug_type = 'Bug';
						}else if($type == 'Doc'){
							$bug_type = 'Documentation';
						}else if($type == 'Dup'){
							$bug_type = 'Duplicate';
						}else if($type == 'Enh'){
							$bug_type = 'Enhancement';
						}else if($type == 'Hel'){
							$bug_type = 'Help Wanted';
						}else if($type == 'Inv'){
							$bug_type = 'Invalid';
						}else if($type == 'Que'){
							$bug_type = 'Question';
						}else if($type == 'Won'){
							$bug_type = "Won't Fix";
						}

						echo "<tr>";
							echo "<td><a href='".SITE_URL."BugTracker/View/$row->id'>View $row->id</a></td>";
							echo "<td class='d-none d-md-table-cell'>$row->package</td>";
							echo "<td>$bug_type</td>";
							echo "<td>";
								echo "<button type='button' class='btn btn-sm $status_button' disabled>{$status}</button>";
							echo "</td>";
							echo "<td class='d-none d-md-table-cell'>$row->priority</td>";
							echo "<td class='d-none d-md-table-cell'>$row->summary</td>";
							/** Check if Comments Helper is installed **/
			        if($DispenserModel->checkDispenserEnabled('CommentsHelper')){
								echo "<td>";
								echo "<i class='fas fa-comment'></i> ";
								echo CommentsHelper::getTotalCommentsCount($row->id, 'BugTracker');
								echo "</td>";
							}
							echo "<td class='d-none d-md-table-cell'>";
								$user_image_display = CurrentUserData::getUserImage($row->creator_userID);
								$username = CurrentUserData::getUserName($row->creator_userID);
								$user_online = CurrentUserData::getUserStatusDot($row->creator_userID);
								echo "<img alt='{$username}'s Profile Picture' src='".SITE_URL.IMG_DIR_PROFILE.$user_image_display."' class='rounded img-fluid' style='height: 25px'> ";
								echo "$user_online<a href='".SITE_URL."Profile/{$username}'> {$username}</a>";
							echo "</td>";
						echo "</tr>";
					}
				}else{
					echo "No Bugs Reported at this time.";
				}
			?>
		</table>
		<?php
			/** Check to see if there is more than one page **/
			if($data['pageLinks'] > "1"){
				echo "<div class='card-footer text-muted' style='text-align: center'>";
				echo $data['pageLinks'];
				echo "</div>";
			}
		?>
  </div>
</div>
