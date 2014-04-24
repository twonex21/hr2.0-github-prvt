/* 'benefit_id' instead of 'id', no 'changed_at' for static tables */
ALTER TABLE `hr_benefits` DROP COLUMN `changed_at`, CHANGE `id` `benefit_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT, CHANGE `name` `name` VARCHAR(80) CHARSET utf8 COLLATE utf8_general_ci NOT NULL;
/* Changed table name, let's use no plurals in table names */
RENAME TABLE `hr_benefits` TO `hr_benefit`;

/* No plurals */
RENAME TABLE `hr_company_benefits` TO `hr_company_benefit`;

/* Let's use more specific names for ids to be used throughout whole database more effectively ('office_id' instead of just 'id')  */
/* Changed 'name' to 'address' */
ALTER TABLE `hr_company_offices` CHANGE `id` `office_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT, CHANGE `company_id` `company_id` INT(11) UNSIGNED NOT NULL, CHANGE `name` `address` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NOT NULL;
/* No plurals */
RENAME TABLE `hr_company_offices` TO `hr_company_office`;

/* Purely naming changes to keep field names consistent throughout the database (see vacancy field for comparison) */
ALTER TABLE `hr_company` CHANGE `amount_of_emploees` `employees_count` VARCHAR(10) CHARSET utf8 COLLATE utf8_general_ci NULL, CHANGE `show_amount_of_views` `show_views_count` TINYINT(1) DEFAULT 1 NOT NULL, CHANGE `show_amount_users_applied` `show_applicants_count` TINYINT(1) DEFAULT 1 NOT NULL;