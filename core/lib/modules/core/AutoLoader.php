<?php
namespace HR\Core;

class AutoLoader
{
	public static $loader;
	
   	private function __construct() {
   		spl_autoload_register(array($this, 'autoloadCore'));
   		spl_autoload_register(array($this, 'autoloadFrontend'));
   		spl_autoload_register(array($this, 'autoloadFacebook'));
   		spl_autoload_register(array($this, 'autoloadLinkedIn'));
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
   	
   	private function autoloadFacebook($className) {
   		$parts = explode('\\', $className);   		
    	
    	$classFile = FB_SDK_DIR . implode("/", $parts) . '.php';    	
    	if(file_exists($classFile)) {
    		require $classFile;
    	}
   	}
   	
   	private function autoloadLinkedIn($className) { 
   		$parts = explode('\\', $className);   		
    	
    	$classFile = LI_SDK_DIR . implode("/", $parts) . '.php';    
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
