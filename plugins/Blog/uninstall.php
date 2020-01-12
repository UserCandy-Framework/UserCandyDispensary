<?php
/**
* Plugin UnInstaller
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.4
*/

/** Add Data needed to Database **/
$uninstall_db_data[] = "
DROP TABLE IF EXISTS `".PREFIX."plugin_blog`;
";
$uninstall_db_data[] = "
DROP TABLE IF EXISTS `".PREFIX."plugin_blog_categories`;
";
$uninstall_db_data[] = "
DROP TABLE IF EXISTS `".PREFIX."plugin_blog_groups`;
";
$uninstall_db_data[] = "
DROP TABLE IF EXISTS `".PREFIX."plugin_blog_images`;
";

/** Remove Plugin Page Permissions **/
$folder_location = "Blog";
$plugin_display_page = CUSTOMDIR.'plugins/'.$folder_location.'/'.$folder_location.'.php';
/** Check to make sure Display page exists **/
if(file_exists($plugin_display_page)){
  /** Get Plugin Page ID **/
  if($page_id = $AdminPanelModel->getPluginPage('plugins/'.$folder_location, $folder_location, $folder_location)){
    /** Remove permissions for the page */
    $AdminPanelModel->deletePagePermissions($page_id);
    if($AdminPanelModel->deletePage($page_id)){
      $new_pages[] = "<b>Removing URL: ".$folder_location."</b> (Plugin - ".$folder_location.")<Br>";
    }
    /** Remove site Links */
    if($AdminPanelModel->deleteSiteLinkDispenser($folder_location, 'header_main')){
      /** Success */
      $new_pages[] = $page_url." Removed from Site Links<Br>";
    }
  }
  /** Get Plugin Page ID **/
  if($page_id2 = $AdminPanelModel->getPluginPage('plugins/'.$folder_location, $folder_location."AutoSave", $folder_location."AutoSave")){
    /** Remove permissions for the page */
    $AdminPanelModel->deletePagePermissions($page_id2);
    if($AdminPanelModel->deletePage($page_id2)){
      $new_pages[] = "<b>Removing URL: ".$folder_location."AutoSave</b> (Plugin - ".$folder_location.")<Br>";
    }
  }
}
