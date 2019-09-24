<?php
/**
* Comments Helper Installer
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version 1.0.0
*/

/** Add Data needed to Database **/
$install_db_data[] = "
CREATE TABLE IF NOT EXISTS `".PREFIX."helper_comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `com_id` int(10) DEFAULT NULL,
  `com_sec_id` int(10) DEFAULT NULL,
  `com_location` varchar(255) DEFAULT NULL,
  `com_user_ip` varchar(50) DEFAULT NULL,
  `com_server` varchar(255) DEFAULT NULL,
  `com_uri` varchar(255) DEFAULT NULL,
  `com_owner_userid` int(10) DEFAULT NULL,
  `com_content` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
";
