<?php
/**
* Widget Installer
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
