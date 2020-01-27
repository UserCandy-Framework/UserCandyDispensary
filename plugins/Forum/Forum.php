<?php
/**
* Messages Display Main File
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.4
*/

use Helpers\Paginator;

/** Get data from URL **/
(empty($viewVars[0])) ? $get_var_1 = "" : $get_var_1 = $viewVars[0];
(empty($viewVars[1])) ? $get_var_2 = "" : $get_var_2 = $viewVars[1];
(empty($viewVars[2])) ? $get_var_3 = "" : $get_var_3 = $viewVars[2];
(empty($viewVars[3])) ? $get_var_4 = "" : $get_var_4 = $viewVars[3];

/** Set the Plugin Directory **/
$plugin_dir = CUSTOMDIR.'plugins/Forum/';

/** Load Messages Plugin Models **/
require_once($plugin_dir.'model.Forum.php');
require_once($plugin_dir.'model.ForumAdmin.php');
require_once($plugin_dir.'helper.ForumStats.php');

$model = new Forum();
// Get data for global forum settings
$forum_title = $model->globalForumSetting('forum_title');
$forum_description = $model->globalForumSetting('forum_description');
$forum_topic_limit = $model->globalForumSetting('forum_topic_limit');
$forum_topic_reply_limit = $model->globalForumSetting('forum_topic_reply_limit');
$forum_max_image_size = $model->globalForumSetting('forum_max_image_size');
$pagesTopic = new Paginator($forum_topic_limit);
$pagesReply = new Paginator($forum_topic_reply_limit);

/* Get user's forum groups data */
$group_forum_perms_post = $model->group_forum_perms($u_id, "users");
$group_forum_perms_mod = $model->group_forum_perms($u_id, "mods");
$group_forum_perms_admin = $model->group_forum_perms($u_id, "admins");

// Forum Topic Replies Image Directory
define('IMG_DIR_FORUM_TOPIC', 'assets/images/forum-pics/topics/');
// Forum Topic Replies Image Directory
define('IMG_DIR_FORUM_REPLY', 'assets/images/forum-pics/replies/');

/** Get the Plugin Page and Display it **/
if($get_var_1 == 'Topics'){
  /** Include the Messages List File **/
  require($plugin_dir.'pages/topics.php');
}else if($get_var_1 == 'NewTopic'){
  /** Include the Messages List File **/
  require($plugin_dir.'pages/newtopic.php');
}else if($get_var_1 == 'Topic'){
  /** Include the Messages List File **/
  require($plugin_dir.'pages/topic.php');
}else if($get_var_1 == 'SearchForum'){
  /** Include the Messages List File **/
  require($plugin_dir.'pages/searchForum.php');
}else{
  /** Include the Messages Home File **/
  require($plugin_dir.'pages/forum_home.php');
}

/** Check to see if we need to hide the sidebar **/
if($_POST['hide_head_foot'] != "true"){
  /** Include the Sidebar **/
  require($plugin_dir.'pages/forum_sidebar.php');
}

?>
