CREATE TABLE IF NOT EXISTS `hr_company_subscription` (
  `company_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `changed_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;