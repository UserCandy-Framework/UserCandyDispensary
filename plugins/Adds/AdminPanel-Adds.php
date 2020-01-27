<?php
/**
* Adds Plugin Main File
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.4
*/

use Models\AdminPanelModel;

/** Get data from URL **/
(empty($viewVars[0])) ? $get_var_1 = "" : $get_var_1 = $viewVars[0];
(empty($viewVars[1])) ? $get_var_2 = "" : $get_var_2 = $viewVars[1];
(empty($viewVars[2])) ? $get_var_3 = "" : $get_var_3 = $viewVars[2];
(empty($viewVars[3])) ? $get_var_4 = "" : $get_var_4 = $viewVars[3];

/** Set the Plugin Directory **/
$plugin_dir = CUSTOMDIR.'plugins/Adds/';

/** If User Not logged in - kick them out **/
if($auth->isLogged()){
  if($usersModel->checkIsAdmin($u_id) == 'false'){
    /** User Not Admin - kick them out **/
    ErrorMessages::push('You are Not Admin', '');
  }
}else{
  /** User Not logged in - kick them out **/
  ErrorMessages::push('You are Not Logged In', 'Login');
}

/** Load Outside classes **/
$AdminPanelModel = new AdminPanelModel();

/** Get the Plugin Page and Display it **/
if($get_var_1 == 'Adds'){
  /** Include the Adds Home File **/
  require($plugin_dir.'pages/adds.php');
}else{
  /** Include the Adds Home File **/
  require($plugin_dir.'pages/adds.php');
}

?>
