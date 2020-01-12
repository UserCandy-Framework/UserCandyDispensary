<?php
/**
* Messages Display Main File
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.4
*/

use Core\Language;
use Models\DispenserModel;
use Helpers\{Paginator,ErrorMessages};

/** Get data from URL **/
(empty($viewVars[0])) ? $get_var_1 = "" : $get_var_1 = $viewVars[0];
(empty($viewVars[1])) ? $get_var_2 = "" : $get_var_2 = $viewVars[1];
(empty($viewVars[2])) ? $get_var_3 = "" : $get_var_3 = $viewVars[2];
(empty($viewVars[3])) ? $get_var_4 = "" : $get_var_4 = $viewVars[3];

/** Set the Plugin Directory **/
$plugin_dir = CUSTOMDIR.'plugins/Wall/';

/** Load Messages Plugin Models **/
require($plugin_dir.'class.Wall.php');
$WallModel = new WallModel();
$DispenserModel = new DispenserModel();
$pages = new Paginator(MESSAGE_PAGEINATOR_LIMIT);

/** If User Not logged in - kick them out **/
if (!$auth->isLogged())
  ErrorMessages::push(Language::show('user_not_logged_in', 'Auth'), 'Login');

/** Check to see if Friends Plugin is installed, if it is show link **/
if($DispenserModel->checkDispenserEnabled('Friends')){
  /** Include the Sidebar **/
  require($plugin_dir.'pages/friends_sidebar.php');
}

/** Include the Messages Home File **/
require($plugin_dir.'pages/wall.php');

/** Check to see if Friends Plugin is installed, if it is show link **/
if($DispenserModel->checkDispenserEnabled('Forum')){
  /** Include the Sidebar **/
  require($plugin_dir.'pages/forum_sidebar.php');
}

?>
