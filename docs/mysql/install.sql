DROP TABLE IF EXISTS `nxc_datalist_filters`;
CREATE TABLE IF NOT EXISTS `nxc_datalist_filters` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `content_class_id` int(11) default NULL,
  `name` varchar(255) NOT NULL,
  `filter_values_serialized` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `nxc_datalist_filters` (`id`, `name`, `filter_values_serialized`) VALUES
(1, NULL, 'No filter limitations', '');