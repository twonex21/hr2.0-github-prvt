CREATE TABLE IF NOT EXISTS `hr_company_page_view` (
  `company_page_view_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `type` enum('USER','COMPANY') NOT NULL,
  `changed_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`company_page_view_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `hr_company` ADD `page_views` INT( 11 ) NOT NULL DEFAULT '0' AFTER `subscribe_for_news` ;