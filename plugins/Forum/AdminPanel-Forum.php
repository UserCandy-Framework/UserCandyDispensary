<?php
/**
* Messages Display Main AdminPanel File
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.3
*/

use Helpers\ErrorMessages;
use Models\AdminPanelModel;

/** Check to see if user is logged in **/
if($auth->isLogged()){
  if($usersModel->checkIsAdmin($u_id) == 'false'){
    /** User Not Admin - kick them out **/
    ErrorMessages::push('You are Not Admin', '');
  }
}else{
  /** User Not logged in - kick them out **/
  ErrorMessages::push('You are Not Logged In', 'Login');
}

/** Get data from URL **/
(empty($viewVars[0])) ? $get_var_1 = "" : $get_var_1 = $viewVars[0];
(empty($viewVars[1])) ? $get_var_2 = "" : $get_var_2 = $viewVars[1];
(empty($viewVars[2])) ? $get_var_3 = "" : $get_var_3 = $viewVars[2];
(empty($viewVars[3])) ? $get_var_4 = "" : $get_var_4 = $viewVars[3];

/** Set the Plugin Directory **/
$plugin_dir = CUSTOMDIR.'plugins/Forum/';

/** Load Messages Plugin Models **/
require_once($plugin_dir.'model.ForumAdmin.php');

$forum = new ForumAdmin();
$model = new AdminPanelModel();

/** Get the Plugin Page and Display it **/
if($get_var_1 == 'Settings'){
  /** Include the Messages List File **/
  require($plugin_dir.'pages/forum_settings.php');
}else if($get_var_1 == 'Categories'){
  /** Include the Messages List File **/
  require($plugin_dir.'pages/forum_categories.php');
}else if($get_var_1 == 'BlockedContent'){
  /** Include the Messages List File **/
  require($plugin_dir.'pages/forum_blocked.php');
}else if($get_var_1 == 'UnpublishedContent'){
  /** Include the Messages List File **/
  require($plugin_dir.'pages/forum_unpublished.php');
}else{
  /** Include the Messages Home File **/
  require($plugin_dir.'pages/forum_settings.php');
}

?>
