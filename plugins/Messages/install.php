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
  CREATE TABLE IF NOT EXISTS `".PREFIX."plugin_messages` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `to_userID` int(11) DEFAULT NULL,
    `from_userID` int(11) DEFAULT NULL,
    `subject` varchar(255) DEFAULT NULL,
    `content` text DEFAULT NULL,
    `date_read` datetime DEFAULT NULL,
    `date_sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `to_delete` varchar(5) NOT NULL DEFAULT 'false',
    `from_delete` varchar(5) NOT NULL DEFAULT 'false',
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
";

/** Setup Plugin Page Permissions **/
$folder_location = "Messages";
$arguments = "(:any)/(:any)/(:any)/(:any)";
$plugin_display_page = CUSTOMDIR.'plugins/'.$folder_location.'/display.php';
$sitemap = "false";
if(!file_exists($plugin_display_page)){
  $plugin_display_page = CUSTOMDIR.'plugins/'.$folder_location.'/'.$folder_location.'.php';
}
/** Check to make sure Display page exists **/
if(file_exists($plugin_display_page)){
  /** Check to see if Plugin is already in Pages **/
  if(!$AdminPanelModel->checkPagesURL($folder_location)){
    /** Add Page to Database */
    if($page_id = $AdminPanelModel->addPluginPage('plugins/'.$folder_location, $folder_location, $folder_location, $arguments, $sitemap)){
      /** New Page added to database.  Let Admin Know it was added */
      $new_pages[] = "<b>URL: ".$folder_location."</b> (Plugin - ".$folder_location.")<Br>";
      /** Add new permission for the page and set as public */
      $AdminPanelModel->addPagePermission($page_id, '1');
      $AdminPanelModel->addPagePermission($page_id, '2');
      $AdminPanelModel->addPagePermission($page_id, '3');
      $AdminPanelModel->addPagePermission($page_id, '4');
      /** Add Settings to Global site settings **/
      $AdminPanelModel->updateSetting('messages_pageinator_limit', '20');
      $AdminPanelModel->updateSetting('messages_quota_limit', '50');
      /** New Route added to database.  Add to site Links */
      if($AdminPanelModel->addSiteLinkDispenser($folder_location, 'header_main', '1')){
        /** Success */
        $new_pages[] = $page_url." Added to Site Links<Br>";
      }
    }
  }
}
