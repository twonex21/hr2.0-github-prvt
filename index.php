<?php
echo "Local Changes here!";
header('Content-Type: text/html; charset=utf-8');

/**
 * Display all errors when APPLICATION_ENV is development
 * 
 */ 
if($_SERVER["APPLICATION_ENV"] == "development") {
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
}

/**
 * This makes our life easier when dealing with paths. 
 * Everything is relative to the application root.
 */
chdir(__DIR__);

/**
 * Checking the case when script is executed via command line
 * 
 */ 
if(strpos(php_sapi_name(), "cli") && isset($argv) && count($argv) > 3) {		
	require 'core/config/main-shell.ini';
	require PATH_DIR.'/frontend/configs/frontend-cli.ini';
	
	list($_GET["controller"], $_GET["action"], $_GET["parameters"]) = $argv;
} else {    	
	require 'core/config/main.ini';
	require PATH_DIR.'/frontend/configs/frontend.ini';
}

// Autoloading files
require LIB_DIR.'modules/core/AutoLoader.php';
HR\Core\AutoLoader::init();

// Starting the session
session_start();    

$fC = new HR\Core\FrontendController();

// Setting current language
$locale = $fC->selectLocale();    

try{
    // Processing the request
    $fC->process();
} catch(HR\Core\MySQLBeanException $e) {
    $fC->delegate('statics', 'error', null, $e);
} catch(HR\Core\BaseException $e) {
    $fC->delegate('statics', 'error',  null, $e);
}

//EOF
?>