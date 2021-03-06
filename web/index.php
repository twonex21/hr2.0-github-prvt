<?php
header('Content-Type: text/html; charset=utf-8');

/**
 * Display all errors when APPLICATION_ENV is development
 * 
 */ 
if($_SERVER['APPLICATION_ENV'] == 'development') {
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

/**
 * This makes our life easier when dealing with paths. 
 * Everything is relative to the file system root.
 */
chdir(dirname(__DIR__));

/**
 * Checking the case when script is executed via command line
 * 
 */ 
if(strpos(php_sapi_name(), 'cli') && isset($argv) && count($argv) > 3) {		
	require '/core/config/main-cli.config.php';
	require PATH_DIR . '/frontend/configs/frontend-cli.config.php';
	
	list($_GET['controller'], $_GET['action'], $_GET['parameters']) = $argv;
} else {    	
	require '/core/config/main.config.php';
	require PATH_DIR . '/frontend/configs/frontend.config.php';
}

// Autoloading files
require LIB_DIR.'modules/core/AutoLoader.php';
HR\Core\AutoLoader::init();

$fC = new HR\Core\FrontendController();

// Setting current language
$locale = $fC->getRequest()->selectLocale(); 

try{
    // Processing the request
    $fC->process();
} catch(HR\Core\MySQLBeanException $e) {
    $fC->delegate('statics', 'error', null, $e);
} catch(HR\Core\BaseException $e) {
    $fC->delegate('statics', 'error', null, $e);
}

//EOF
?>
