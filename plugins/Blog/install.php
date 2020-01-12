<?php
/**
* Plugin Installer
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.4
*/

/** Add Data needed to Database **/
$install_db_data[] = "
CREATE TABLE IF NOT EXISTS `".PREFIX."plugin_blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_category` int(11) NOT NULL DEFAULT '0',
  `blog_title` varchar(255) DEFAULT NULL,
  `blog_description` varchar(255) DEFAULT NULL,
  `blog_url` varchar(255) DEFAULT NULL,
  `blog_content` text,
  `blog_keywords` varchar(255) DEFAULT NULL,
  `blog_owner_id` int(11) DEFAULT NULL,
  `blog_featured` int(11) NOT NULL DEFAULT '0',
  `blog_publish` int(1) NOT NULL DEFAULT '0',
  `edit_timestamp` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
";
$install_db_data[] = "
CREATE TABLE IF NOT EXISTS `".PREFIX."plugin_blog_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
";
$install_db_data[] = "
CREATE TABLE IF NOT EXISTS `".PREFIX."plugin_blog_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_group` varchar(50) DEFAULT NULL,
  `groupID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
";
$install_db_data[] = "
CREATE TABLE IF NOT EXISTS `".PREFIX."plugin_blog_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `blog_id` int(11) NOT NULL,
  `blogImage` varchar(255) DEFAULT NULL,
  `defaultImage` int(11) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
";
$install_db_data[] = "
INSERT INTO `".PREFIX."plugin_blog_categories` (`id`, `title`, `description`) VALUES
(1, 'Test Stuff', 'Test Category and Stuff'),
(2, 'Sample', 'Sample Category that has a lot to do with samples and stuff.'),
(3, 'World', 'Stuff about world things and such.'),
(4, 'Hometown', 'Just a simple hometown kind of thing.');
";


/** Setup Plugin Page Permissions **/
$folder_location = "Blog";
$arguments = "(:any)/(:any)/(:any)/(:any)/(:any)";
$sitemap = "true";
$plugin_display_page = CUSTOMDIR.'plugins/'.$folder_location.'/'.$folder_location.'.php';
/** Check to make sure Display page exists **/
if(file_exists($plugin_display_page)){
  /** Check to see if Plugin is already in Pages **/
  if(!$AdminPanelModel->checkPagesURL($folder_location)){
    /** Add Page to Database */
    if($page_id = $AdminPanelModel->addPluginPage('plugins/'.$folder_location, $folder_location, $folder_location, $arguments, $sitemap)){
      /** New Page added to database.  Let Admin Know it was added */
      $new_pages[] = "<b>URL: ".$folder_location."</b> (Plugin - ".$folder_location.")<Br>";
      /** Add new permission for the page and set as public */
      $AdminPanelModel->addPagePermission($page_id, '0'); // Public Group
      //$AdminPanelModel->addPagePermission($page_id, '1'); // New Member Group
      //$AdminPanelModel->addPagePermission($page_id, '2'); // Member Group
      //$AdminPanelModel->addPagePermission($page_id, '3'); // Moderator Group
      //$AdminPanelModel->addPagePermission($page_id, '4'); // Administrator Group
      if($page_id2 = $AdminPanelModel->addPluginPage('plugins/'.$folder_location, $folder_location."AutoSave", $folder_location."AutoSave", "", "false", "Default", "0")){
        $AdminPanelModel->addPagePermission($page_id2, '1'); // New Member Group
        $AdminPanelModel->addPagePermission($page_id2, '2'); // Member Group
        $AdminPanelModel->addPagePermission($page_id2, '3'); // Moderator Group
        $AdminPanelModel->addPagePermission($page_id2, '4'); // Administrator Group
        $new_pages[] = "<b>URL: ".$folder_location."AutoSave</b> (Plugin - ".$folder_location.")<Br>";
      }
      /** New Route added to database.  Add to site Links */
      if($AdminPanelModel->addSiteLinkDispenser($folder_location, 'header_main', '0')){
        /** Success */
        $new_pages[] = $page_url." Added to Site Links<Br>";
      }
    }
  }
}
