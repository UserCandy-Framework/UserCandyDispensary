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

/** Get Cat Title **/
$monthName = date("F", mktime(0, 0, 0, $thir_data, 10));
$date_data = $sec_data.' '.$monthName;

/** Collect Data for view **/
$data['title'] = $date_data.' Blogs';
$data['site_description'] = 'Welcome to '.SITE_TITLE.' '.$date_data.' Blogs';
$data['blogs_data'] = $BlogModel->getBlogsDataDate($sec_data, $thir_data, $pages->getLimit($current_page, BLOG_PAGES_LIMIT));

/** Get Pages Totals **/
$total_num_items = $BlogModel->getBlogsDataDateCount($sec_data, $thir_data);
$pages->setTotal($total_num_items);
$pageFormat = SITE_URL."Blog/Archives/1/"; // URL page where pages are
$data['pageLinks'] = $pages->pageLinks($pageFormat, "/".$sec_data."/".$thir_data."/", $current_page);

/** Setup Breadcrumbs **/
$data['breadcrumbs'] = "
  <li class='breadcrumb-item'><a href='".SITE_URL."Blog'>".$data['blog_title']."</a></li>
  <li class='breadcrumb-item active'>".$data['title']."</li>
";

?>
