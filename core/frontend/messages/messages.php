<?php
/*
 * Message Constants
*/

// Page Titles
define('PT_EDIT_USER_PROFILE', 'Edit Profile');
define('PT_EDIT_COMPANY_PROFILE', 'Edit Company Profile');
define('PT_OPEN_VACANCY', 'Open Vacancy');

// Validation
define('VALIDATION_FILE_UPLOAD_ERROR', 'Some error occured during file upload');
define('VALIDATION_FILE_FILESIZE_SMALL','File must be larger than 1 Kb');
define('VALIDATION_FILE_FILESIZE_LARGE','File must not be larger than 500 Kb');
define('VALIDATION_FILE_TYPE','Incorrect file format. Supported formats are txt, doc, xls, ppt, pdf');
define('VALIDATION_PICTURE_UPLOAD_ERROR', 'Some error occured during picture upload');
define('VALIDATION_PICTURE_FILESIZE_SMALL','File must be larger than 1 Kb');
define('VALIDATION_PICTURE_FILESIZE_LARGE','File must not be larger than 500 Kb');
define('VALIDATION_PICTURE_PXSIZE_SMALL','Picture must be larger than 50x50 pxs');
define('VALIDATION_PICTURE_PXSIZE_LARGE','Picture size exceeds 600x600 pxs');
define('VALIDATION_PICTURE_TYPE','Incorrect file format. Supported formats are jpg, jpeg, png, gif');


define("VALIDATION_MESSAGES", 
		serialize(
				array(
					"notEmpty" => "This field is required", 
					"isEmailAddress" => "Invalid e-mail format", 
					"isPasswordLength" => "Password is shorter than 6 characters", 
					"passwordsMatch" => "Passwords don't match",
					"isLatin" => "Only latin characters accepted",
					"isDate" => "Invalid date format",
					"isFutureDate" => "Please specify date in the future",
					"isLinkedIn" => "Invalid linkedIn link"
				)
		)
);

// Messages
// MSG_{Controller}_{Action}_{Type} format
define('MSG_USER_CREATE_SUCCESS', 'Your profile has been successfully updated!');
define('MSG_USER_CREATE_ERROR', 'Ooops, seems like some problem occured updating your profile. Please try again later.');
define('MSG_VACANCY_OPEN_SUCCESS', 'Vacancy has been successfully saved!');
define('MSG_VACANCY_OPEN_ERROR', 'Ooops, seems like some problem occured saving vacancy. Please try again later.');


?>