CREATE TABLE `hr_vacancy_view` (
  `view_id` int(11) unsigned NOT NULL auto_increment,
  `vacancy_id` int(11) unsigned NOT NULL,
  `role` enum('USER','COMPANY') NOT NULL default 'USER',
  `role_user_id` int(11) unsigned NOT NULL,
  `created_at` datetime NOT NULL default '0000-00-00 00:00',
  PRIMARY KEY  (`view_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `hr_vacancy` ADD COLUMN `views` SMALLINT(6) DEFAULT 0 NULL AFTER `status`, CHANGE `created_at` `created_at` DATETIME DEFAULT '0000-00-00 00:00:00' NULL;

