CREATE TABLE `hr_company_hiring` (
  `company_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created_at` datetime default '0000-00-00 00:00:00',
  UNIQUE KEY `user_comp_idx` (`company_id`,`user_id`),
  KEY `h_user_cnsnt` (`user_id`),
  CONSTRAINT `h_user_cnsnt` FOREIGN KEY (`user_id`) REFERENCES `hr_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `h_comp_cnsnt` FOREIGN KEY (`company_id`) REFERENCES `hr_company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
