CREATE TABLE `hr_user_search` (
  `user_id` int(11) unsigned NOT NULL,
  `name` varchar(60) NOT NULL,
  `location` varchar(100) default NULL,
  `experience` text,
  `skills` text,
  PRIMARY KEY  (`user_id`),
  FULLTEXT KEY `name_idx` (`name`),
  FULLTEXT KEY `skills_idx` (`skills`),
  FULLTEXT KEY `exp_idx` (`experience`),
  FULLTEXT KEY `loc_idx` (`location`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;