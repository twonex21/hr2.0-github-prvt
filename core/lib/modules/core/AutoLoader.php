<?php
namespace HR\Core;

class AutoLoader
{
	public static $loader;
	
   	private function __construct() {
   		spl_autoload_register(array($this, 'autoloadCore'));
   		spl_autoload_register(array($this, 'autoloadFrontend'));
   	}
   	
   	private function autoloadCore($className) {
   		$parts = explode('\\', $className);   		
    	
    	$classFile = CORE_DIR . end($parts) . '.php';
    	if(file_exists($classFile)) {
    		require_once $classFile;
    	}
   	}
   	
   	private function autoloadFrontend($className) {
   		$parts = explode('\\', $className);
   		// Getting rid of the standard HR part
    	array_shift($parts);
    	
    	$classFile = FRONTEND_DIR . implode("/", $parts) . '.php';    	
    	if(file_exists($classFile)) {
    		require $classFile;
    	}
   	}
   	
   	public static function init() {
   		if(self::$loader == null) {
   			self::$loader = new self();
   		}
   		
   		return self::$loader;
   	}
}
//EOF
?>
