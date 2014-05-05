<?php

// Frontend configuration
// Setting if dev environment
define('APPLICATION_DEV', 'development');

// Main templates
define('BASE_TEMPLATE_DIR', 'core/frontend/templates');

// This and other configuration files
define('BASE_CONFIG_DIR', '../../configs');

// Cache dir
define('BASE_CACHE_DIR', 'core/frontend/cache');

// Locale files
define('BASE_LOCALE_DIR', '../../../locale/');

// Compiled templates
define('BASE_TEMPLATE_C_DIR', 'core/frontend/templates_c');

// Main template
define('BASE_MAIN_TEMPLATE', 'main.tpl');
define('BASE_POPUP_TEMPLATE', '');


// Security configs
define('SESSION_USER_ATTRIBUTES',
	serialize(
		array('ID', 'idHash', 'mail', 'firstName', 'lastName', 'fullName', 'pictureKey', 'resumeKey', 'registrationDate')
	)
);

define('SESSION_COMPANY_ATTRIBUTES',
	serialize(
		array('ID', 'idHash', 'mail', 'name', 'phone', 'contactPerson', 'pictureKey', 'registrationDate', 'numberOfVacancies')
	)
);

// Array of controller => array(action1, action2, ...) entries representing pages available only after user authorization
define('USER_SECURED_ACTIONS',
	serialize(
		array(
			'user' => array('create'),
			'vacancy' => array('apply')
		)
	)
);

// Array of controller => array(action1, action2, ...) entries representing pages available only after company authorization
define('COMPANY_SECURED_ACTIONS',
	serialize(
		array(
			'company' => array('create'),
			'vacancy' => array('open')
		)
	)
);

// Shortcuts - actions with shortened names to call directly http://hr.am/action
define('DIRECT_ACTIONS',
	serialize(
		array(
			'tos' => array(
				'controller' => 'static',
				'action' => 'page',
				'parameters' => array('p' => 'tos')
			)
		)
	)
);

// Action not to be tracked
define('NOT_TRACKABLE_ACTIONS',
	serialize(
		array(
			'api' => array(
			 
			),
			
			'auth' => array(
				
			)
		)
	)
);


define('TPL_STYLESHEET_MAPPING', 
		serialize(
			array(
				'Main\home.tpl' => array('mCustomScrollbar'),
				'Company\create.tpl' => array('glDatePicker.flatwhite'),
				'User\create.tpl' => array('glDatePicker.flatwhite'),
				'User\profile.tpl' => array('araks'),
			)
		)
);

define('TPL_SCRIPT_MAPPING',
		serialize(
			array(
				'Main\home.tpl' => array('jquery.mCustomScrollbar.min', 'jquery.jcarousel.min', 'ajax'),
				'Company\create.tpl' => array('ajax'),
				'User\create.tpl' => array('ajax'),
				'User\profile.tpl' => array('jquery.jcarousel.min'),
			)
		)
);

// File paths
define('TEMP_FILE_PATH', TEMP_DIR . 'tmp_%s.%s');
define('USER_PICTURE_PATH', MEDIA_DIR . 'user_pictures/usr_%s.%s');
define('USER_SQUARE_PICTURE_PATH', MEDIA_DIR . 'user_pictures/square/usr_%s.%s');
define('RESUME_PATH', MEDIA_DIR . 'user_resumes/%s.%s');
define('COMPANY_PICTURE_PATH', MEDIA_DIR . 'company_pictures/cmpn_%s.%s');
define('COMPANY_SQUARE_PICTURE_PATH', MEDIA_DIR . 'company_pictures/square/cmpn_%s.%s');
define('VACANCY_PATH', MEDIA_DIR . 'company_vacancy_files/%s.%s');

?>