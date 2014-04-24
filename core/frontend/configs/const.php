<?php
// Locales
define('HR_LOCALES', serialize(array('en' => 'en_GB')));

// Constants
define('NAVIGATE_DEFAULT_CONTROLLER', 'main');
define('NAVIGATE_DEFAULT_ACTION', 'home');

define('USER', 'user');
define('COMPANY', 'company');
define('RESUME', 'resume');
define('VACANCY', 'vacancy');

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

// External API keys

?>