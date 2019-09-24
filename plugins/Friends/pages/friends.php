<?php
/**
* Friends Plugin Friends List Page
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version 1.0.0
*/

/** Get Data from URL **/
(empty($get_var_1)) ? $set_order_by = "ID-ASC" : $set_order_by = $get_var_1;
(empty($get_var_2)) ? $current_page = "1" : $current_page = $get_var_2;
(empty($get_var_3)) ? $search = null : $search = $get_var_3;

/** Check for orderby selection **/
$data['orderby'] = $set_order_by;

/** Set total number of rows for paginator **/
// Check to see if member is searching for a user
if(isset($search)){
    $total_num_friends = $FriendsModel->getFriendsCountSearch($currentUserData[0]->userID, $search);
    $search_url = "/".$search;
}else{
    $total_num_friends = CurrentUserData::getFriendsCount($currentUserData[0]->userID);
    $search_url = "";
}
$pages->setTotal($total_num_friends);

/** Send page links to view **/
$pageFormat = SITE_URL."Friends/$set_order_by/"; // URL page where pages are
$data['pageLinks'] = $pages->pageLinks($pageFormat, $search_url, $current_page);
$data['current_page_num'] = $current_page;

/** Collect Data page for view **/
/** Check to see if user is searching friends **/
if(isset($search)){
    $data['title'] = "Search My Friends";
    $data['welcomeMessage'] = Language::show('search_found', 'Friends').' '.$total_num_friends.' '.Language::show('matches_for', 'Friends').': '.$search;
    // Let the view know user is searching
    $data['search'] = $search;
}else{
    $data['title'] = "My Friends";
    $data['welcomeMessage'] = "Welcome to Your Friends List";
    // Let the view know user is not searching
    $data['search'] = false;
}

/** Setup Breadcrumbs **/
$data['breadcrumbs'] = "<li class='breadcrumb-item active'>My Friends</li>";

/** Get List of All Current User's Friends **/
$friends_list = $FriendsModel->friends_list($currentUserData[0]->userID, $set_order_by, $pages->getLimit($current_page, FRIENDS_PAGEINATOR_LIMIT), $search);


/** Check to make sure current user has friends **/
if(!empty($friends_list)){
?>

<div class="col-lg-6 col-md-4 col-sm-12">
	<div class="card mb-3">
		<div class="card-header h4">
			<?=$data['title']?>
		</div>
    <table class="table table-striped table-hover responsive">
				<thead><tr><th colspan='4'><?=$data['welcomeMessage'];?></th></tr></thead>
        <thead>
            <tr>
                <th colspan='2'>
					<?php
					if(empty($data['orderby'])){
						$obu_value = "UN-DESC";
						$obu_icon = "";
					}else if($data['orderby'] == "UN-DESC"){
						$obu_value = "UN-ASC";
						$obu_icon = "<i class='fas fa-caret-down'></i>";
					}else if($data['orderby'] == "UN-ASC"){
						$obu_value = "UN-DESC";
						$obu_icon = "<i class='fas fa-caret-up'></i>";
					}else{
						$obu_value = "UN-ASC";
						$obu_icon = "";
					}
					if(isset($search)){
						$search_url = "/$search";
					}else{
						$search_url = "";
					}
					// Setup the order by id button
					echo "<a href='".SITE_URL."Friends/$obu_value/".$data['current_page_num'].$search_url."' class=''>".Language::show('friends_username', 'Friends')." $obu_icon</button>";
					?>
				</th>
        <th><?=Language::show('members_firstname', 'Members'); ?></th>
        <th></th>
            </tr>
        </thead>
        <tbody>

                <?php
                    foreach ($friends_list as $var) {
                        /** Make sure we are showing friend and not current user **/
                        if($var->uid1 == $currentUserData[0]->userID){
                            $friend_id = $var->uid2;
                        }else if($var->uid2 == $currentUserData[0]->userID){
                            $friend_id = $var->uid1;
                        }
                        /** Check to make sure there is a friend id **/
                        if(isset($friend_id)){
                            $member_username = CurrentUserData::getUserName($friend_id);
                            $member_firstName = CurrentUserData::getUserFirstName($friend_id);
                            $member_userImage = CurrentUserData::getUserImage($friend_id);
                            $user_online = CurrentUserData::getUserStatusDot($friend_id);
                            echo "<tr>
                                    <td width='20px'><img src=".SITE_URL.IMG_DIR_PROFILE.$member_userImage." class='rounded' style='height: 25px'></td>
                                    <td>$user_online<a href='".SITE_URL."Profile/{$member_username}'> {$member_username}</a></td>
                                    <td>{$member_firstName}</td>
                                    <td><div class='float-right'><a href='".SITE_URL."Profile/{$member_username}' class='btn btn-sm btn-primary'>View Profile</a> <a href='".SITE_URL."Friends/UnFriend/{$member_username}' class='btn btn-sm btn-secondary'>UnFriend</a></div></td>
                                  </tr>";
                        }
                    }
                ?>
        </tbody>
    </table>

		<?php
			/** Check to see if there is more than one page **/
			if($pageLinks > "1"){
				echo "<div class='card-footer text-muted' style='text-align: center'>";
				echo $pageLinks;
				echo "</div>";
			}
		?>
  </div>
</div>

<?php
}else{
?>
<div class="col-lg-9 col-md-8 col-sm-8">
	<div class="card mb-3">
		<div class="card-header h4">
			<h1><?=$data['title']?></h1>
		</div>
        <div class='card-body'>
            Sorry, No results for your search. :(
        </div>
    </div>
</div>
<?php
}
?>
