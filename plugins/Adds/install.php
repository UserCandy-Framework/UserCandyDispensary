<?php
/**
* Plugin Installer
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.4
*/


/** Setup Plugin Page Permissions **/
$folder_location = "Adds";
$arguments = "(:any)/(:any)/(:any)/(:any)";
$plugin_adminpanel_display_page = CUSTOMDIR.'plugins/'.$folder_location.'/AdminPanel-'.$folder_location.'.php';

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
      if($drop_down_for = $AdminPanelModel->addSiteLink($admin_folder_location, $admin_folder_location, $admin_folder_location." - ".$admin_folder_location, 'nav_admin', '0', '', '4', 'fab fa-fw fa-adn')){
        /** Success */
        $new_pages[] = $admin_folder_location." Added to Site Links<Br>";
      }
    }
  }
}
