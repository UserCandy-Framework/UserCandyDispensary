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
CREATE TABLE IF NOT EXISTS `".PREFIX."plugin_demo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `demo_version` varchar(255) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";
$install_db_data[] = "
INSERT INTO `".PREFIX."plugin_demo` (`demo_version`) VALUES
('1.0.0');
";

/** Setup Plugin Page Permissions **/
$folder_location = "DemoPlugin";
$arguments = "(:any)/(:any)/(:any)/(:any)";
$sitemap = "false";
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
      if($AdminPanelModel->addSiteLink($folder_location, $folder_location, $folder_location." - ".$folder_location, 'header_main', '0', '')){
        /** Success */
        $new_pages[] = $page_url." Added to Site Links<Br>";
      }
    }
  }
}
