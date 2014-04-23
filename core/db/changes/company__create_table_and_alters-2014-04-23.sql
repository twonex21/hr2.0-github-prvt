ALTER TABLE `hr_company` ADD `logo_key` VARCHAR( 10 ) NULL DEFAULT NULL AFTER `contact_person`;


ALTER TABLE `hr_company` ADD `linkedin` VARCHAR( 200 ) NULL AFTER `contact_person` ,
ADD `facebook` VARCHAR( 200 ) NULL AFTER `linkedin` ,
ADD `twitter` VARCHAR( 200 ) NULL AFTER `facebook` ;


ALTER TABLE `hr_company` ADD `new_vacancies` BOOLEAN NOT NULL DEFAULT TRUE AFTER `logo_key` ,
ADD `subscribe_for_news` BOOLEAN NOT NULL DEFAULT TRUE AFTER `new_vacancies` ;


ALTER TABLE `hr_company` ADD `additional_info` TEXT NULL DEFAULT NULL AFTER `name` ;


ALTER TABLE `hr_company` ADD `amount_of_emploees` VARCHAR( 10 ) NULL DEFAULT NULL AFTER `logo_key` ;


ALTER TABLE `hr_company` ADD `show_amount_of_views` BOOLEAN NOT NULL DEFAULT TRUE AFTER `amount_of_emploees` ;


ALTER TABLE `hr_company` ADD `show_amount_users_applied` BOOLEAN NOT NULL DEFAULT TRUE AFTER `show_amount_of_views` ;


CREATE TABLE IF NOT EXISTS `hr_company_offices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `name` varchar(300) NOT NULL,
  `changed_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `hr_benefits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `changed_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

INSERT INTO `hr_benefits` (`id`, `name`, `changed_at`) VALUES
(1, 'Insurance', '0000-00-00 00:00:00'),
(2, 'Trainings', '0000-00-00 00:00:00'),
(3, 'Competitive Salary', '0000-00-00 00:00:00'),
(4, 'Business Trips', '0000-00-00 00:00:00'),
(5, 'Transportation', '0000-00-00 00:00:00'),
(6, 'Wellness programs (sport, gym)', '0000-00-00 00:00:00'),
(7, 'Discount programs', '0000-00-00 00:00:00'),
(8, 'Bonuses', '0000-00-00 00:00:00'),
(9, '13th salary', '0000-00-00 00:00:00'),
(10, 'Professional Growth', '0000-00-00 00:00:00'),
(11, 'Provision of housing', '0000-00-00 00:00:00')


CREATE TABLE IF NOT EXISTS `hr_company_benefits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `benefit_id` int(11) NOT NULL,
  `changed_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;