CREATE TABLE `hr_company_workers` (
  `user_id` int(11) unsigned NOT NULL,
  `company_id` int(11) unsigned NOT NULL,
  `created_at` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`user_id`,`company_id`),
  KEY `comp_cnsnt` (`company_id`),
  CONSTRAINT `comp_cnsnt` FOREIGN KEY (`company_id`) REFERENCES `hr_company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_cnsnt` FOREIGN KEY (`user_id`) REFERENCES `hr_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;