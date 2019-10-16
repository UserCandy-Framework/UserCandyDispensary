<?php
/**
* Messages Display Main File
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version 1.0.0
*/

use Helpers\{Paginator,ErrorMessages};

/** Get data from URL **/
(empty($viewVars[0])) ? $get_var_1 = "" : $get_var_1 = $viewVars[0];
(empty($viewVars[1])) ? $get_var_2 = "" : $get_var_2 = $viewVars[1];
(empty($viewVars[2])) ? $get_var_3 = "" : $get_var_3 = $viewVars[2];
(empty($viewVars[3])) ? $get_var_4 = "" : $get_var_4 = $viewVars[3];

/** Set the Plugin Directory **/
$plugin_dir = CUSTOMDIR.'plugins/Messages/';

/** Load Messages Plugin Models **/
require($plugin_dir.'class.Messages.php');
$MessagesModel = new Messages();
$pages = new Paginator(MESSAGE_PAGEINATOR_LIMIT);

/** If User Not logged in - kick them out **/
if (!$auth->isLogged())
  ErrorMessages::push(Language::show('user_not_logged_in', 'Auth'), 'Login');

/** Include the Sidebar **/
require($plugin_dir.'pages/messages_sidebar.php');

/** Get the Plugin Page and Display it **/
if($get_var_1 == 'Inbox'){
  /** Include the Messages List File **/
  require($plugin_dir.'pages/messages_list.php');
}else if($get_var_1 == 'Outbox'){
  /** Include the Messages List File **/
  require($plugin_dir.'pages/messages_list.php');
}else if($get_var_1 == 'New'){
  /** Include the Messages List File **/
  require($plugin_dir.'pages/message_new.php');
}else if($get_var_1 == 'View'){
  /** Include the Messages List File **/
  require($plugin_dir.'pages/message_display.php');
}else{
  /** Include the Messages Home File **/
  require($plugin_dir.'pages/messages.php');
}

?>
