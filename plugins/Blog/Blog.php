<?php
/**
* Friends Plugin Main File
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.4
*/

use Helpers\Paginator;

/** Get data from URL **/
(empty($viewVars[0])) ? $view_page = null : $view_page = $viewVars[0];
(empty($viewVars[1])) ? $blog_id = null : $blog_id = $viewVars[1];
(empty($viewVars[2])) ? $current_page = "1" : $current_page = $viewVars[2];
(empty($viewVars[3])) ? $sec_data = null : $sec_data = $viewVars[3];
(empty($viewVars[4])) ? $thir_data = null : $thir_data = $viewVars[4];

/** Set the Plugin Directory **/
$plugin_dir = CUSTOMDIR.'plugins/Blog/';

/** Load Messages Plugin Models **/
require($plugin_dir.'model.Blog.php');
$BlogModel = new Blog();

/** Set Basic Defaults **/
define('BLOG_PAGES_LIMIT', '20');
$pages = new Paginator(BLOG_PAGES_LIMIT);
define('IMG_DIR_BLOG', ROOTDIR.'assets/images/blog/');

/** Check is user is Admin **/
$data['isAdmin'] = $usersModel->checkIsAdmin($auth->user_info());

/** Get Blog Settings Data **/
$data['blog_title'] = $AdminPanelModel->getSettings('blog_title');
$data['blog_description'] = $AdminPanelModel->getSettings('blog_description');
$data['blog_max_image_size'] = $AdminPanelModel->getSettings('blog_max_image_size');

/** Get Blog Categories **/
$data['blog_categories'] = $BlogModel->getBlogCategories();
/** Get Blog Archives **/
$data['blog_archives'] = $BlogModel->getBlogArchives();

/** If User Not logged in - kick them out **/
//if (!$auth->isLogged())
//  ErrorMessages::push(Language::show('user_not_logged_in', 'Auth'), 'Login');

/** Get the Plugin Page and Display it **/
if(empty($view_page) || $view_page == 'All'){
  /** Include the Blogs Page **/
  require($plugin_dir.'pages/blogs.php');
}else if($view_page == 'Categories'){
  /** Include the Messages Home File **/
  require($plugin_dir.'pages/blog_categories.php');
  require($plugin_dir.'pages/blogs_search.php');
}else if($view_page == 'Archives'){
  /** Include the Messages Home File **/
  require($plugin_dir.'pages/blog_archives.php');
  require($plugin_dir.'pages/blogs_search.php');
}else if($view_page == 'MyBlogs'){
  /** If User Not logged in - kick them out **/
  if (!$auth->isLogged())
   ErrorMessages::push(Language::show('user_not_logged_in', 'Auth'), 'Login');
  /** Include the Messages Home File **/
  require($plugin_dir.'pages/blog_myblogs.php');
  require($plugin_dir.'pages/blogs_search.php');
}else if($view_page == 'CreateBlog'){
  /** If User Not logged in - kick them out **/
  if (!$auth->isLogged())
   ErrorMessages::push(Language::show('user_not_logged_in', 'Auth'), 'Login');
  /** Include the Messages Home File **/
  require($plugin_dir.'pages/edit_blog.php');
}else{
  $blog_id = $view_page;
  /** Include the Messages Home File **/
  require($plugin_dir.'pages/view_blog.php');
}

/** Include the Sidebar **/
require($plugin_dir.'pages/blog_sidebar.php');

?>
