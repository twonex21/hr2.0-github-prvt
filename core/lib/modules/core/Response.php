<?php
namespace HR\Core;

// Move in code after development finished
use \RuntimeException;

class Response
{			
	
	/**
     * Json output array
     */
    public $jsonArray;    
	
    public function __construct() {}
	    
    public function jsonPrepare($status = FAIL) {
    	$this->jsonArray = array();
    	$this->jsonSetStatus($status);
    }
    
    public function jsonSetStatus($status) {
 		$this->jsonSet(JSON_STATUS, $status);
    }
    
    public function jsonSetErrorMessage($message) {
 		$this->jsonSet(JSON_ERROR_MESSAGE, $message);
    }
    
    public function jsonErrorMessagesPush($key, $value) {
    	if(!key_exists(JSON_ERROR_MESSAGES, $this->jsonArray)) {
    		$this->jsonSet(JSON_ERROR_MESSAGES, array());
    	}
    	
 		$this->jsonArray[JSON_ERROR_MESSAGES][$key] = $value;
    }
    
    public function jsonSet($key, $value) {
    	if(is_array($this->jsonArray)) {
 			$this->jsonArray[$key] = $value;
 		}
    }
    
    public function jsonRemove($key) {
    	if(is_array($this->jsonArray) && isset($this->jsonArray[$key])) {
 			unset($this->jsonArray[$key]);
 		}
    }
    
    public function jsonOutput() {
    	if(is_array($this->jsonArray)) {
 			self::setHeader('Content-type', 'application/json');
 			echo json_encode($this->jsonArray, true);
 		}
    }
    
    public static function setHeader($name, $value) {    
 		   	header($name . ': ' . $value);
    }
}
?>
