<?php
/**
* Helper UnInstaller
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.4
*/

/** Add Data needed to Database **/
$uninstall_db_data[] = "
DROP TABLE IF EXISTS `".PREFIX."helper_demo`;
";
