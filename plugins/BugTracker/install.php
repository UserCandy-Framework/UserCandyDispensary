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
CREATE TABLE IF NOT EXISTS `".PREFIX."plugin_bugtracker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'New',
  `priority` varchar(255) DEFAULT NULL,
  `version` varchar(255) DEFAULT NULL,
  `server` varchar(255) DEFAULT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `folder` varchar(255) DEFAULT NULL,
  `assigned_userID` int(11) DEFAULT NULL,
  `creator_userID` int(11) DEFAULT NULL,
  `modifi_userID` int(11) DEFAULT NULL,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
";

/** Setup Plugin Page Permissions **/
$folder_location = "BugTracker";
$arguments = "(:any)/(:any)/(:any)/(:any)";
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
      /** New Route added to database.  Add to site Links */
      if($AdminPanelModel->addSiteLinkDispenser($folder_location, 'header_main', '0')){
        /** Success */
        $new_pages[] = $page_url." Added to Site Links<Br>";
      }
    }
  }
}
