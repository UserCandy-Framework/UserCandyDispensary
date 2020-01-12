<?php
/**
* UserApplePie v4 Blog View Plugin Home
*
* UserApplePie - Blog Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 1.0.0 for UAP v.4.3.0
*/

/** Blog Display Page View **/

use Core\Language;
use Helpers\{ErrorMessages,SuccessMessages,Form,Request,CurrentUserData,BBCode};

/** Collect Data for view **/
$data['title'] = 'My Blogs';
$data['site_description'] = 'Welcome to your Blogs';
$data['current_url'] = 'MyBlogs';
$data['blogs_data'] = $BlogModel->getBlogsDataUser($auth->currentSessionInfo()['uid'], $pages->getLimit($current_page, BLOG_PAGES_LIMIT));

/** Get Pages Totals **/
$total_num_items = $BlogModel->getBlogsDataUserCount($auth->currentSessionInfo()['uid']);
$pages->setTotal($total_num_items);
$pageFormat = SITE_URL."MyBlogs/"; // URL page where pages are
$data['pageLinks'] = $pages->pageLinks($pageFormat, null, $current_page);

/** Setup Breadcrumbs **/
$data['breadcrumbs'] = "
  <li class='breadcrumb-item'><a href='".SITE_URL."Blog'>".$data['blog_title']."</a></li>
  <li class='breadcrumb-item active'>".$data['title']."</li>
";

?>
