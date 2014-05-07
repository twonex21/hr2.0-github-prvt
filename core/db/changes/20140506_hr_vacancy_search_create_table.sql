CREATE TABLE `hr_vacancy_search` (
  `vacancy_id` int(11) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `location` varchar(80) NOT NULL,
  `info` text,
  `skills` text,
  PRIMARY KEY  (`vacancy_id`),
  FULLTEXT KEY `skills_idx` (`skills`),
  FULLTEXT KEY `title_idx` (`title`),
  FULLTEXT KEY `ftxt_idx` (`company_name`,`location`),
  FULLTEXT KEY `info_idx` (`info`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;