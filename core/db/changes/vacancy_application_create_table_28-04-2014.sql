CREATE TABLE `hr_vacancy_application` (
  `appl_id` int(11) unsigned NOT NULL auto_increment,
  `vacancy_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created_at` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`appl_id`),
  UNIQUE KEY `vac_user_idx` (`vacancy_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;