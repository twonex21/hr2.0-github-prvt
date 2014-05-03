<?php
// Locales
define('HR_LOCALES', serialize(array('en' => 'en_GB')));

// Constants
define('NAVIGATE_DEFAULT_CONTROLLER', 'main');
define('NAVIGATE_DEFAULT_ACTION', 'home');

define('USER', 'USER');
define('COMPANY', 'COMPANY');
define('RESUME', 'RESUME');
define('VACANCY', 'VACANCY');

define('SQL_UNPREPARED_QUERY', 1);
define('SQL_PREPARED_QUERY', 2);

define('JSON_STATUS', 'status');
define('JSON_ERROR_MESSAGE', 'message');
define('JSON_ERROR_MESSAGES', 'messages');
define('FAIL', 'FAIL');
define('SUCCESS', 'SUCCESS');

// Template constants
define('TPL_SESSION', '_HR_SESSION');
define('TPL_MESSAGE', '_HR_MESSAGE');

// Message constants
define('MSG_TYPE_ERROR', 'error');
define('MSG_TYPE_SUCCESS', 'success');

// Vacancy statuse
define('VACANCY_STATUS_ACTIVE', 'ACTIVE');
define('VACANCY_STATUS_INACTIVE', 'INACTIVE');

define('SMALL_IMAGE_SIZE',  60);
define('MIDDLE_IMAGE_SIZE', 100);
define('LARGE_IMAGE_SIZE',  200);

define('HR_MIN_PASSWORD_LENGTH', 6);
define('HR_PICTURE_EXTENSIONS', serialize(array('jpeg', 'png', 'gif')));
define('HR_FILE_EXTENSIONS', serialize(array('txt', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf')));

define('TYPE_FILE', 'FILE');
define('TYPE_PICTURE', 'PICTURE');

define('FILE_ALLOWED_EXTENSIONS', serialize(array('txt', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf')));
define('FILE_ALLOWED_TYPES', serialize(array('text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/pdf')));
define('FILE_FILESIZE', serialize(array('min' => 1, 'max' => 500))); // Min and max in Kbs
define('PICTURE_ALLOWED_EXTENSIONS', serialize(array('jpg', 'jpeg', 'png', 'gif')));
define('PICTURE_ALLOWED_TYPES', serialize(array('image/jpeg', 'image/pjpeg', 'image/png', 'image/gif')));
define('PICTURE_FILESIZE', serialize(array('min' => 1, 'max' => 500))); // Min and max in Kbs
define('PICTURE_PXSIZE', serialize(array('min' => 50, 'max' => 600))); // Min and max in pxs
define('PICTURE_CROP_DIMENSION', 100); // Min and max in pxs

define('UNIVER_DEGREES', serialize(array('Incomplete higher', 'Bachelor', 'Masters', 'PhD', 'Candidate of Science', 'Doctor of Science', 'MBA', 'Residency training')));
define('LANGUAGES', serialize(array('Armenian', 'Russian', 'English', 'French', 'German', 'Arabic', 'Turkish', 'Spanish', 'Italian', 'Georgian', 'Chinese')));
define('LANGUAGE_LEVELS', serialize(array('Elementary', 'Intermediate', 'Advanced', 'Native')));
define('SOFT_SKILL_LEVELS', serialize(array('Start', 'Average', 'Good', 'Advanced', 'Brilliant')));
define('SKILL_LEVEL_MAP', 
		serialize(
			array(
				'1' => array('score' => 29, 'text' => 'Start Level'), 
				'2' => array('score' => 49, 'text' => 'Satisfactory'), 
				'3' => array('score' => 65, 'text' => 'Good'), 
				'4' => array('score' => 74, 'text' => 'Advanced'), 
				'5' => array('score' => 89, 'text' => 'Expert'), 
				'5+' => array('score' => 95, 'text' => 'Expert'), 
			)
		)
);

// Matching weights
define('MATCHING_SKILLS_WEIGHT', 0.5);
define('MATCHING_EXPERIENCE_WEIGHT', 0.25);
define('MATCHING_EDUCATION_WEIGHT', 0.1);
define('MATCHING_LANGUAGES_WEIGHT', 0.1);
define('MATCHING_SOFTSKILLS_WEIGHT', 0.05);

// Matching levels
define('MATCHING_LEVELS', 
		serialize(
			array(
				'30' => array('level' => 'low', 'text' => 'You might want to use vacancy search to find vacancies that suit you better'),
				'50' => array('level' => 'average', 'text' => 'You might want to use vacancy search to find vacancies that suit you better'),
				'75' => array('level' => 'good', 'text' => 'Rather big probability to get shortlisted if applied'),
				'90' => array('level' => 'high', 'text' => 'We strongly encourage you to pay attention to this vacancy'),
				'100' => array('level' => 'very high', 'text' => 'Seems like you\'ve finally found each other')
	  		)
  		)
);

define('MATCHING_APPLICATION_THRESHOLD', 50);

// External API keys

?>