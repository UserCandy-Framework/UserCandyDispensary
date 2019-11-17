<?php
/**
* Plugin Installer
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version 1.0.0
*/

/** Add Data needed to Database **/
$install_db_data[] = "
CREATE TABLE IF NOT EXISTS `".PREFIX."plugin_forum_cat` (
  `forum_id` int(20) NOT NULL AUTO_INCREMENT,
  `forum_name` varchar(255) DEFAULT NULL,
  `forum_title` varchar(255) DEFAULT NULL,
  `forum_cat` varchar(255) DEFAULT NULL,
  `forum_des` text DEFAULT NULL,
  `forum_perm` int(20) NOT NULL DEFAULT '1',
  `forum_order_title` int(11) NOT NULL DEFAULT '1',
  `forum_order_cat` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`forum_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";

$install_db_data[] = "
CREATE TABLE IF NOT EXISTS `".PREFIX."plugin_forum_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `forum_group` varchar(50) DEFAULT NULL,
  `groupID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";

$install_db_data[] = "
CREATE TABLE IF NOT EXISTS `".PREFIX."plugin_forum_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `imageName` varchar(255) DEFAULT NULL,
  `imageLocation` varchar(255) DEFAULT NULL,
  `imageSize` int(11) DEFAULT NULL,
  `forumID` int(11) DEFAULT NULL,
  `forumTopicID` int(11) DEFAULT NULL,
  `forumTopicReplyID` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";

$install_db_data[] = "
CREATE TABLE IF NOT EXISTS `".PREFIX."plugin_forum_posts` (
  `forum_post_id` int(20) NOT NULL AUTO_INCREMENT,
  `forum_id` int(20) DEFAULT NULL,
  `forum_user_id` int(20) DEFAULT NULL,
  `forum_title` varchar(255) DEFAULT NULL,
  `forum_url` varchar(255) DEFAULT NULL,
  `forum_content` text DEFAULT NULL,
  `forum_publish` int(1) NOT NULL DEFAULT '0',
  `forum_edit_date` varchar(20) DEFAULT NULL,
  `forum_status` int(11) NOT NULL DEFAULT '1',
  `subscribe_email` varchar(10) NOT NULL DEFAULT 'true',
  `allow` varchar(11) NOT NULL DEFAULT 'TRUE',
  `hide_reason` varchar(255) DEFAULT NULL,
  `hide_userID` int(11) DEFAULT NULL,
  `hide_timestamp` datetime DEFAULT NULL,
  `forum_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`forum_post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";

$install_db_data[] = "
CREATE INDEX index_posts_title ON ".PREFIX."plugin_forum_posts(forum_title);
";

$install_db_data[] = "
CREATE FULLTEXT INDEX index_posts_content ON ".PREFIX."plugin_forum_posts(forum_content);
";

$install_db_data[] = "
CREATE TABLE IF NOT EXISTS `".PREFIX."plugin_forum_post_replies` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `fpr_post_id` int(20) DEFAULT NULL,
  `fpr_id` int(20) DEFAULT NULL,
  `fpr_user_id` int(20) DEFAULT NULL,
  `fpr_content` text DEFAULT NULL,
  `forum_publish` int(1) NOT NULL DEFAULT '0',
  `subscribe_email` varchar(10) NOT NULL DEFAULT 'true',
  `fpr_edit_date` varchar(20) DEFAULT NULL,
  `allow` varchar(11) NOT NULL DEFAULT 'TRUE',
  `hide_reason` varchar(255) DEFAULT NULL,
  `hide_userID` int(11) DEFAULT NULL,
  `hide_timestamp` datetime DEFAULT NULL,
  `fpr_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";

$install_db_data[] = "
CREATE FULLTEXT INDEX index_posts_content ON ".PREFIX."plugin_forum_post_replies(fpr_content);
";

$install_db_data[] = "
CREATE TABLE IF NOT EXISTS `".PREFIX."plugin_forum_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_title` varchar(255) DEFAULT NULL,
  `setting_value` varchar(255) DEFAULT NULL,
  `setting_value_2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";

$install_db_data[] = "
INSERT INTO `".PREFIX."plugin_forum_cat` (`forum_id`, `forum_name`, `forum_title`, `forum_cat`, `forum_des`, `forum_perm`, `forum_order_title`, `forum_order_cat`) VALUES
(1, 'forum', 'Forum', 'Welcome', 'Welcome to the Forum.', 1, 1, 1);
";

$install_db_data[] = "
INSERT INTO `".PREFIX."plugin_forum_settings` (`id`, `setting_title`, `setting_value`, `setting_value_2`) VALUES
(1, 'forum_on_off', 'Enabled', ''),
(2, 'forum_title', 'Forum', ''),
(3, 'forum_description', 'Welcome to the Forum', ''),
(4, 'forum_topic_limit', '20', ''),
(5, 'forum_topic_reply_limit', '10', ''),
(6, 'forum_posts_group_change_enable', 'true', ''),
(7, 'forum_posts_group_change', '15', ''),
(8, 'forum_max_image_size', '800,600', '');
";

$install_db_data[] = "
CREATE TABLE IF NOT EXISTS `".PREFIX."plugin_forum_tracker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `forum_id` int(11) DEFAULT NULL,
  `last_visit` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";

$install_db_data[] = "
CREATE TABLE IF NOT EXISTS `".PREFIX."plugin_forum_post_tracker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `forum_post_id` int(11) DEFAULT NULL,
  `forum_reply_id` int(11) DEFAULT NULL,
  `tracker_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT='Keeps track of all forum posts and replies for better sort';
";

$install_db_data[] = "
INSERT INTO `".PREFIX."plugin_forum_groups` (`id`, `forum_group`, `groupID`) VALUES
(1, 'users', 1),
(2, 'users', 2),
(3, 'users', 3),
(4, 'users', 4),
(5, 'mods', 3),
(6, 'mods', 4),
(7, 'admins', 4);
";

/** Setup Plugin Page Permissions **/
$folder_location = "Forum";
$arguments = "(:any)/(:any)/(:any)/(:any)";
$plugin_display_page = CUSTOMDIR.'plugins/'.$folder_location.'/display.php';
$sitemap = "true";
if(!file_exists($plugin_display_page)){
  $plugin_display_page = CUSTOMDIR.'plugins/'.$folder_location.'/'.$folder_location.'.php';
}
$plugin_adminpanel_display_page = CUSTOMDIR.'plugins/'.$folder_location.'/AdminPanel-'.$folder_location.'.php';

/** Check to make sure Display page exists **/
if(file_exists($plugin_display_page)){
  /** Check to see if Plugin is already in Pages **/
  if(!$AdminPanelModel->checkPagesURL($folder_location)){
    /** Add Page to Database */
    if($page_id = $AdminPanelModel->addPluginPage('plugins/'.$folder_location, $folder_location, $folder_location, $arguments, $sitemap)){
      /** New Page added to database.  Let Admin Know it was added */
      $new_pages[] = "<b>URL: ".$folder_location."</b> (Plugin - ".$folder_location.")<Br>";
      /** Add new permission for the page and set as public */
      $AdminPanelModel->addPagePermission($page_id, '0');
      /** New Route added to database.  Add to site Links */
      if($AdminPanelModel->addSiteLinkDispenser($folder_location, 'header_main', '0')){
        /** Success */
        $new_pages[] = $folder_location." Added to Site Links<Br>";
      }
    }
  }
}

/** Check to make sure Admin Panel Display page exists **/
if(file_exists($plugin_adminpanel_display_page)){
  /** Check to see if Plugin is already in Pages **/
  $admin_folder_location = 'AdminPanel-'.$folder_location;
  if(!$AdminPanelModel->checkPagesURL($admin_folder_location)){
    /** Add Page to Database */
    if($page_id = $AdminPanelModel->addPluginPage('plugins/'.$folder_location, $admin_folder_location, $admin_folder_location, $arguments, 'false', 'AdminPanel')){
      /** New Page added to database.  Let Admin Know it was added */
      $new_pages[] = "<b>URL: ".$admin_folder_location."</b> (Plugin - ".$admin_folder_location.")<Br>";
      /** Add new permission for the page and set as admin */
      $AdminPanelModel->addPagePermission($page_id, '4');
      /** New Route added to database.  Add to site Links */
      if($drop_down_for = $AdminPanelModel->addSiteLink($admin_folder_location, $admin_folder_location, $admin_folder_location." - ".$admin_folder_location, 'nav_admin', '1', '', '4', 'fa fa-fw fa-wrench')){
        $AdminPanelModel->addSiteDDLink('Settings', $admin_folder_location."/Settings/", $admin_folder_location." Settings", 'nav_admin', '0', null, $drop_down_for, '4', 'fa fa-fw fa-cog');
        $AdminPanelModel->addSiteDDLink('Categories', $admin_folder_location."/Categories/", $admin_folder_location." Categories", 'nav_admin', '0', null, $drop_down_for, '4', 'fa fa-fw fa-list');
        $AdminPanelModel->addSiteDDLink('Blocked Content', $admin_folder_location."/BlockedContent/", $admin_folder_location." Blocked Content", 'nav_admin', '0', null, $drop_down_for, '4', 'fa fa-fw fa-ban');
        $AdminPanelModel->addSiteDDLink('Unpublished Content', $admin_folder_location."/UnpublishedContent/", $admin_folder_location." Unpublished Content", 'nav_admin', '0', null, $drop_down_for, '4', 'fas fa-fw fa-file-import');
        /** Success */
        $new_pages[] = $admin_folder_location." Added to Site Links<Br>";
      }
    }
  }
}
