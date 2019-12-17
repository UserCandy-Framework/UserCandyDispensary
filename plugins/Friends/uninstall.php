<?php
/**
* Plugin UnInstaller
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.3
*/

/** Add Data needed to Database **/
$uninstall_db_data[] = "
DROP TABLE IF EXISTS `".PREFIX."plugin_friends`;
";

/** Remove Plugin Page Permissions **/
$folder_location = "Friends";
$plugin_display_page = CUSTOMDIR.'plugins/'.$folder_location.'/'.$folder_location.'.php';
/** Check to make sure Display page exists **/
if(file_exists($plugin_display_page)){
  /** Get Plugin Page ID **/
  if($page_id = $AdminPanelModel->getPluginPage('plugins/'.$folder_location, $folder_location, $folder_location)){
    /** New Page added to database.  Let Admin Know it was added */
    $new_pages[] = "<b>Removing URL: ".$folder_location."</b> (Plugin - ".$folder_location.")<Br>";
    /** Add new permission for the page and set as public */
    $AdminPanelModel->deletePagePermissions($page_id);
    $AdminPanelModel->deletePage($page_id);
    /** New Route added to database.  Add to site Links */
    if($AdminPanelModel->deleteSiteLinkDispenser($folder_location, 'header_main')){
      /** Success */
      $new_pages[] = $page_url." Removed from Site Links<Br>";
    }
  }
}
