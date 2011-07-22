CREATE TABLE `canvas-letters` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(50) DEFAULT NULL,
  `blockColour` varchar(6) NOT NULL,
  `canvasColour` varchar(6) NOT NULL,
  `blockSize` int(2) NOT NULL,
  `textString` text NOT NULL,
  `clearance` int(2) NOT NULL,
  `breakWord` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ordering` varchar(20) NOT NULL,
  `do_loop` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `animate` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `speed` int(2) NOT NULL DEFAULT '5',  
  `views` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(20) NOT NULL,
  `dateadded` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `canvas-letters`
--


INSERT INTO `canvas-letters` VALUES(1, '8hy5e', 'ff9900', '000000', 15, 'Quidquid latine dictum sit; altum sonatur.', 10, 1, 'default', 1, 1, 5, 0, '192.168.0.7', '2010-10-13 11:40:52');