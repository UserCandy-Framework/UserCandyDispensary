<?php
/**
* Helper Installer
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.4
*/

/** Add Data needed to Database **/
$install_db_data[] = "
CREATE TABLE IF NOT EXISTS `".PREFIX."helper_demo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `demo_version` varchar(255) NOT NULL DEFAULT '0',
  `notes` TEXT NULL DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";
$install_db_data[] = "
INSERT INTO `".PREFIX."helper_demo` (`demo_version`) VALUES
('1.0.0');
";
