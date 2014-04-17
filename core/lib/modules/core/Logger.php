<?php
namespace HR\Core;

class Logger
{	
	const HEADER = "HEADER";
	const FOOTER = "FOOTER";
	const SUB_HEADER = "SUB_HEADER";
	const LINE = "LINE";
	const FORMATTED_LINE = "FORMATTED";
	const ERROR = "ERROR";
	
	const HEADER_FORMAT = "\n\r\n\r%s (%s)";
	const FOOTER_FORMAT = "\n\r%s (%s)";
	const SUB_HEADER_FORMAT = "\n\r\t%s";
	const LINE_FORMAT = "\n\r%s";
	const FORMATTED_LINE_FORMAT = "\n\r\n\r\t\t%s";
	const ERROR_FORMAT = "\n\r\n\r\n\rError : %s - %s";
			
    private static $_instance = null;
    
    private $filePath;
	private $outputStr;
	private $logType;

    private function __construct($logType = '', $filePath = '') {
    	$this->logType = $logType;
    	
    	if($filePath != '' && file_exists($filePath)) {
    		$this->filePath = $filePath;
    	} else {
	    	if(!is_dir(LOG_DIR)) {
	    		// If no such directory, creating it
	    		if(mkdir(LOG_DIR)) {
	    			// Giving full permission in order to let different users (root/apache) write in the same log file 
	    			chmod(LOG_DIR, 766);
	    		}
	    	}
	    	
	    	if($this->logType == self::ERROR) {
	    		$this->filePath = LOG_DIR."error_log_".date("dmY").".txt";
	    	} else {	    		    	
		    	$this->filePath = LOG_DIR."access_log_".date("dmY").".txt";
	    	}
    	}
    	
    	$this->outputStr = "";
    }

       
    public static function getInstance($logType = '', $filePath = '') {
    	if(self::$_instance === null) {
    		self::$_instance = new self($logType, $filePath);
    	}
    	
    	return self::$_instance;
    }
        
    
    public function append($logLine, $lineType = self::ERROR) {
    	$this->outputStr .= self::formatLine($this->logType, $logLine, $lineType);    	
    }
    
    public static function formatLine($logType, $logLine, $lineType = self::ERROR) {
    	$formattedLine = '';
    	switch($lineType) {
    		case self::HEADER :
    			$formattedLine = sprintf(self::HEADER_FORMAT, strtoupper($logLine), date('Y-m-d H:i:s'));
    			break;
			case self::FOOTER :
    			$formattedLine = sprintf(self::FOOTER_FORMAT, strtoupper($logLine), date('Y-m-d H:i:s'));
    			break;
			case self::SUB_HEADER :
    			$formattedLine = sprintf(self::SUB_HEADER_FORMAT, $logLine);
    			break;
			case self::LINE :
    			$formattedLine = sprintf(self::LINE_FORMAT, $logLine);
    			break;
			case self::FORMATTED_LINE :
    			$formattedLine = sprintf(self::FORMATTED_LINE_FORMAT, $logLine);
    			break;
			default:
				if($logType == self::ERROR) {
					$formattedLine = sprintf(self::ERROR_FORMAT, date('Y-m-d H:i:s'), $logLine);
				}
    	}
    	
    	return $formattedLine;
    }
    
    private function write() {
    	if($this->outputStr !== "") {
    		$isNew = true;
    		if(file_exists($this->filePath)) {
			    $isNew = false;
			}
			
    		$logFile = fopen($this->filePath, 'a');
    		fwrite($logFile, $this->outputStr);
        	fclose($logFile);        	        	
        	
        	if($isNew) {
        		chmod($this->filePath, 766);
        	}
    	}
    }
    
    
    public static function writeLog($logLine, $logType = '') {
    	if(!is_dir(LOG_DIR)) {
    		// If no such directory, creating it
    		if(mkdir(LOG_DIR)) {
    			// Giving full permission in order to let different users (root/apache) write in the same log file 
    			chmod(LOG_DIR, 766);
    		}
    	}
    	
    	if($logType == self::ERROR) {
    		$filePath = LOG_DIR."error_log_".date("dmY").".txt";
    	} else {	    		    	
	    	$filePath = LOG_DIR."access_log_".date("dmY").".txt";
    	}    	
    	
    	if($logLine !== "") {
    		$isNew = true;
    		if(file_exists($filePath)) {
			    $isNew = false;
			}
			
    		$logFile = fopen($filePath, 'a');
    		fwrite($logFile, self::formatLine($logType, $logLine));
        	fclose($logFile);        	        	
        	
        	if($isNew) {
        		chmod($filePath, 766);
        	}
    	}
    }

    
    public function __destruct() {   
    	$this->write();
    }
}
?>