<?php
/**
* Friends Plugin Main File
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version 1.0.0
*/

/** Get data from URL **/
(empty($viewVars[0])) ? $get_var_1 = "" : $get_var_1 = $viewVars[0];
(empty($viewVars[1])) ? $get_var_2 = "" : $get_var_2 = $viewVars[1];
(empty($viewVars[2])) ? $get_var_3 = "" : $get_var_3 = $viewVars[2];
(empty($viewVars[3])) ? $get_var_4 = "" : $get_var_4 = $viewVars[3];

/** Set the Plugin Directory **/
$plugin_dir = CUSTOMDIR.'plugins/DemoPlugin/';

/** Load Messages Plugin Models **/
require($plugin_dir.'class.Demo.php');
$DemoModel = new Demo();

/** If User Not logged in - kick them out **/
//if (!$auth->isLogged())
//  ErrorMessages::push(Language::show('user_not_logged_in', 'Auth'), 'Login');

/** Include the Sidebar **/
require($plugin_dir.'pages/demo_sidebar.php');

/** Get the Plugin Page and Display it **/
if($get_var_1 == 'Demo'){
  /** Include the Messages Home File **/
  require($plugin_dir.'pages/demo_demo.php');
}else{
  /** Include the Messages Home File **/
  require($plugin_dir.'pages/demo_home.php');
}

?>
