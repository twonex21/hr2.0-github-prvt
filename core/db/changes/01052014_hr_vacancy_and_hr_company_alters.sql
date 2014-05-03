ALTER TABLE `hr_vacancy` ADD COLUMN `industry_id` TINYINT(4) NULL AFTER `company_id`;


ALTER TABLE `hr_company` ADD COLUMN `is_top` TINYINT(1) DEFAULT 0 NOT NULL AFTER `subscribe_for_news`;