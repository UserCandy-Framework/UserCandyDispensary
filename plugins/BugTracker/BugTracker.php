<?php
/**
* Friends Plugin Main File
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.4
*/

/** Get data from URL **/
(empty($viewVars[0])) ? $get_var_1 = "" : $get_var_1 = $viewVars[0];
(empty($viewVars[1])) ? $get_var_2 = "" : $get_var_2 = $viewVars[1];
(empty($viewVars[2])) ? $get_var_3 = "" : $get_var_3 = $viewVars[2];
(empty($viewVars[3])) ? $get_var_4 = "" : $get_var_4 = $viewVars[3];

/** Set the Plugin Directory **/
$plugin_dir = CUSTOMDIR.'plugins/BugTracker/';

/** Load Messages Plugin Models **/
require($plugin_dir.'model.BugTracker.php');
$BugTrackerModel = new BugTracker();

/** If User Not logged in - kick them out **/
//if (!$auth->isLogged())
//  ErrorMessages::push(Language::show('user_not_logged_in', 'Auth'), 'Login');

/** Check to see if Current user is a Mod or Admin **/
$data['is_admin'] = $auth->checkIsAdmin($currentUserData[0]->userID);
$data['is_mod'] = $auth->checkIsMod($currentUserData[0]->userID);
if($data['is_admin'] == true || $data['is_mod'] == true){
	$userModAdmin = true;
}

/** Include the Sidebar **/
require($plugin_dir.'pages/bugs_sidebar.php');

/** Get the Plugin Page and Display it **/
if($get_var_1 == 'View'){
  /** Include the Messages Home File **/
  require($plugin_dir.'pages/bugs_view.php');
}else if($get_var_1 == 'Submit'){
  /** Include the Messages Home File **/
  require($plugin_dir.'pages/bugs_submit.php');
}else if($get_var_1 == 'Edit'){
  /** Include the Messages Home File **/
  require($plugin_dir.'pages/bugs_edit.php');
}else{
  /** Include the Messages Home File **/
  require($plugin_dir.'pages/bugs_home.php');
}

?>
