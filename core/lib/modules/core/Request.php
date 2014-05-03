<?php
namespace HR\Core;

// Move in code after development finished
use \RuntimeException;

class Request
{			
	
	/**
     * Request body parameters ($_POST)     
     */
    public $request;

    /**
     * Query string parameters ($_GET)     
     */
    public $query;

    /**
     * Server and execution environment parameters ($_SERVER)
     */
    public $server;

    /**
     * Uploaded files ($_FILES)     
     */
    public $files;

    /**
     * Cookies ($_COOKIE)     
     */
    public $cookies;	
	
	
    public function __construct() {
    	$this->initialize($_POST, $_GET, $_SERVER, $_FILES, $_COOKIE);
    }
	
    
    private function initialize($request = array(), $query = array(), $server = array(), $files = array(), $cookies = array()) {    	
    	$this->request = new RequestWrapper($request);
        $this->query = new RequestWrapper($query);
        $this->server = new RequestWrapper($server);
        $this->files = new RequestWrapper($files);
        $this->cookies = new RequestWrapper($cookies);        
    }
    
        
    /**
     * Select the language based on tld and then on accept-language header
     */
    public function selectLocale($language = 'en_GB') {               
        setlocale (LC_ALL, $language);
        // Setting the .po file
        putenv("LANG=".$language);
        //bindtextdomain('messages', $this->server->get('DOCUMENT_ROOT') . 'locale');
        textdomain('messages');
        bind_textdomain_codeset('messages', 'UTF-8');        

        return $language;
    }     
    
    
    public static function getIpAddress() {
	    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
	        if (array_key_exists($key, $_SERVER) === true) {
	            foreach (explode(',', getenv($key)) as $ip) {
	                $ip = trim($ip);
					
	                if($_SERVER["APPLICATION_ENV"] == "development") {
	                	return $ip;
	                } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
	                    return $ip;
	                }
	            }
	        }
	    }
	    
	    return '';
	}        
}
?>
