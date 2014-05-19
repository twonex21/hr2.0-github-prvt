ALTER TABLE `hr_user` ADD COLUMN `ckey` VARCHAR(40) NULL AFTER `external_id`, ADD COLUMN `ctime` VARCHAR(10) NULL AFTER `ckey`;

ALTER TABLE `hr_company` ADD COLUMN `ckey` VARCHAR(40) NULL AFTER `company_id`, ADD COLUMN `ctime` VARCHAR(10) NULL AFTER `ckey`;