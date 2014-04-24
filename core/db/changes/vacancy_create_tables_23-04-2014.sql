CREATE TABLE `hr_vacancy` (
  `vacancy_id` int(11) unsigned NOT NULL auto_increment,
  `company_id` int(11) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `location` varchar(80) NOT NULL,
  `info` text,
  `show_applicants_count` tinyint(4) unsigned NOT NULL default '1',
  `show_viewers_count` tinyint(4) unsigned NOT NULL default '1',
  `show_wanttowork_count` tinyint(4) unsigned NOT NULL default '1',
  `file_key` varchar(255) default NULL,
  `deadline` date NOT NULL default '0000-00-00',
  `status` enum('ACTIVE','INACTIVE') NOT NULL default 'ACTIVE',
  `created_at` datetime NOT NULL default '0000-00-00 00:00:00',
  `changed_at` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`vacancy_id`),
  KEY `company_id_cnsnt` (`company_id`),
  CONSTRAINT `company_id_cnsnt` FOREIGN KEY (`company_id`) REFERENCES `hr_company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hr_vacancy_benefit` (
  `vacancy_id` int(11) unsigned NOT NULL,
  `benefit_id` tinyint(4) unsigned NOT NULL,
  `changed_at` datetime NOT NULL default '0000-00-00 00:00:00',
  KEY `ben_vacancy_id` (`vacancy_id`),
  KEY `vac_benefit_id` (`benefit_id`),
  CONSTRAINT `vac_benefit_id` FOREIGN KEY (`benefit_id`) REFERENCES `hr_benefit` (`benefit_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ben_vacancy_id` FOREIGN KEY (`vacancy_id`) REFERENCES `hr_vacancy` (`vacancy_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hr_vacancy_education` (
  `vacancy_id` int(11) unsigned NOT NULL default '0',
  `industry_id` tinyint(4) unsigned NOT NULL default '0',
  `degree` varchar(20) NOT NULL default '',
  `changed_at` datetime default '0000-00-00 00:00:00',
  KEY `edu_vacancy_id` (`vacancy_id`),
  KEY `edu_industry_id` (`industry_id`),
  CONSTRAINT `edu_industry_id` FOREIGN KEY (`industry_id`) REFERENCES `hr_industry` (`industry_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `edu_vacancy_id` FOREIGN KEY (`vacancy_id`) REFERENCES `hr_vacancy` (`vacancy_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hr_vacancy_experience` (
  `vacancy_id` int(11) unsigned NOT NULL default '0',
  `industry_id` tinyint(4) unsigned NOT NULL default '0',
  `spec_id` smallint(6) unsigned NOT NULL default '0',
  `years` varchar(4) NOT NULL default '',
  `changed_at` datetime default '0000-00-00 00:00:00',
  KEY `exp_vacancy_id` (`vacancy_id`),
  CONSTRAINT `exp_vacancy_id` FOREIGN KEY (`vacancy_id`) REFERENCES `hr_vacancy` (`vacancy_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hr_vacancy_language` (
  `vacancy_id` int(11) unsigned NOT NULL,
  `language` varchar(20) NOT NULL default '',
  `level` varchar(20) NOT NULL default '',
  `changed_at` datetime default '0000-00-00 00:00:00',
  KEY `lang_vacancy_id` (`vacancy_id`),
  CONSTRAINT `lang_vacancy_id` FOREIGN KEY (`vacancy_id`) REFERENCES `hr_vacancy` (`vacancy_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hr_vacancy_skill` (
  `vacancy_id` int(11) unsigned NOT NULL default '0',
  `skill_id` int(11) unsigned NOT NULL default '0',
  `years` varchar(4) NOT NULL default '',
  `changed_at` datetime default '0000-00-00 00:00:00',
  KEY `skill_vacancy_id` (`vacancy_id`),
  CONSTRAINT `skill_vacancy_id` FOREIGN KEY (`vacancy_id`) REFERENCES `hr_vacancy` (`vacancy_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hr_vacancy_soft_skill` (
  `vacancy_id` int(11) unsigned NOT NULL default '0',
  `soft_id` smallint(6) NOT NULL default '0',
  `level` varchar(10) NOT NULL default '',
  `changed_at` datetime default '0000-00-00 00:00:00',
  KEY `soft_skill_vacancy` (`vacancy_id`),
  CONSTRAINT `soft_skill_vacancy` FOREIGN KEY (`vacancy_id`) REFERENCES `hr_vacancy` (`vacancy_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
