<?php
/**
* Friends Plugin Main File
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.4
*/

/** Get data from URL **/
(empty($viewVars[0])) ? $view_page = null : $view_page = $viewVars[0];
(empty($viewVars[1])) ? $blog_id = null : $blog_id = $viewVars[1];
(empty($viewVars[2])) ? $current_page = "1" : $current_page = $viewVars[2];
(empty($viewVars[3])) ? $sec_data = null : $sec_data = $viewVars[3];

/** Set the Plugin Directory **/
$plugin_dir = CUSTOMDIR.'plugins/Blog/';

/** Load Messages Plugin Models **/
require($plugin_dir.'model.Blog.php');
$BlogModel = new Blog();

/** If User Not logged in - kick them out **/
if (!$auth->isLogged())
 ErrorMessages::push(Language::show('user_not_logged_in', 'Auth'), 'Login');

/** Get the Auto Save Plugin Page and Display it **/
require($plugin_dir.'pages/edit_blog.php');

?>
