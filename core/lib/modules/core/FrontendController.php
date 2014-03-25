<?php
namespace HR\Core;

require PATH_DIR.'/frontend/configs/const.ini';
require PATH_DIR.'/frontend/messages/properties.ini';

class FrontendController
{            		
    /**
     * Parses the get parameters hidden in the url
     */
    private function parseParameters($parameters) {
        $reqP = array();
        $lk = '';

        if($parameters == '/' || $parameters == '') {
            return null;
        }

        $parameters = str_replace(' ', '=', $parameters);

        // Find seperator char first in the path
        $pos = strpos($parameters,'/t/');
        if($pos === false) {
            //TODO handle error differently
            //throw new BaseException('url manipulation','The URL was manipulated','403');
            header("Location: /notfound");
        }

        $param = substr($parameters, 0, $pos);
        
        // Removing the first and the last /
        if($str = trim(str_replace('/', ' ',$param))) {
            $tmp = explode(' ',$str);
            // After splitting odd=key even=value
            $cnt = count($tmp);               
            for($i = 0; $i < $cnt; $i++) {
                // Build the array
                if(($i % 2) == 0) {
                    $lk = $tmp[$i];
                } else {
                    $reqP[$lk] = $tmp[$i];
                }
            }

            return $reqP;
        } else {
            return null;
        }
    }

    /**
     * Process a request, calls delegate to handle the request
     * @param array theRequest The request array containing cleaned input
     * variables
     */
    public function process() {
    	$controller = NAVIGATE_DEFAULT_CONTROLLER;
        $action = NAVIGATE_DEFAULT_ACTION;
    	$query = null;        
        $parameters = null;

        if(isset($_GET['parameters'])) {
            $parameters = $this->parseParameters($_GET['parameters']);
        }
        
        foreach($_GET as $k => $v) {
            if($k != 'controller' && $k != 'action' && $k != 'parameters' && $k != 'query') {
                $parameters[$k] = $v;
            }
        }	
        
        if(isset($_GET["query"]) && $_GET["query"] != "" && !(isset($_GET["controller"]) && isset($_GET["action"]))) {
        	$directActions = unserialize(DIRECT_ACTIONS);        	
        	$query = strtolower($_GET["query"]);        	          	
        	
        	// Handling short url cases (direct actions)
        	if(array_key_exists($query, $directActions)) {
        		if(is_array($directActions[$query])) {
        			$controller = $directActions[$query]["controller"];
        			$action = $directActions[$query]["action"];
        			
        			if(isset($directActions[$query]["parameters"])) {
        				foreach($directActions[$query]["parameters"] as $key => $value) {
        					$parameters[$key] = $value;
        				}
        			}
        			
        		} else {
	        		$controller = $directActions[$query];
	        		$action = $query;
        		}
        	} else {
				header("Location: /notfound");				
        	}
				
        } elseif(isset($_GET["controller"]) && isset($_GET["action"])) {
	        $controller = $_GET['controller'];
	        $action = $_GET['action'];	        
        }
        
        // Let delegate handle the rest
        $this->delegate($controller, $action, $parameters);
    }

    /**
     * Process a request, load the module and let it handle the rest
     * @param string targetController The controller handling the request
     * @param string targetAction The action requested
     */
    public function delegate($targetController, $targetAction, $reqParams = null, $objectParams = null) {    	
        $newController = '';
        $newAction = '';

        if(!isset($targetController) || !isset($targetAction)) {
            throw new BaseException('Controller', 'No controller or no action');
        }

        // The module that handles our request
        $controller = ucfirst(strtolower($targetController));
        // The class that will perform the action requested
        $action = ucfirst(strtolower($targetAction));

        $filepath = LIB_DIR.'modules/frontend/'.$controller.'/'.$action.'Action.php';
		
        if(file_exists($filepath))
            require $filepath;
        else {
            header("Location: /notfound");
            return;
        }
        
        // Getting action class name
        $actionClass = 'HR\\' . $controller . '\\' . $action . 'Action';
        // Create an instance of action class
        $actionInstance = new $actionClass();              

        if($objectParams != null) {
            $actionInstance->objectParams = $objectParams;
        }        

        if ($this->locale == null) {
            $this->locale = $this->selectLocale();
        }

       	// Setting global vars to be visible in all templates.
       	//$GLOBALS["tplVars"] = $this->setGlobalTplVars();              	
		
        // Call the perform method
        $actionInstance->perform($reqParams);
        
    }

    /**
     * True HTTP redirect
     */
    public function redirect($targetController, $targetAction) {
        if($targetController == '' || $targetAction == '') {
            return false;
        }

        // The module that has handles our request
        $controller = strtolower($targetController);
        // The class that will perform the action requested
        $action = strtolower($targetAction);

        $target = sprintf('Location: http://%s/%s/%s/', $_SERVER['HTTP_HOST'], $controller, $action);
        header($target);
        exit;
    }

    /**
     * Select the language based on tld and then on accept-language header
     */
    public function selectLocale() {
        global $localeArray;
        $language = 'en_GB';     	
    	
        if(isset($_SESSION['curr_lang']) && isset($localeArray[$_SESSION['curr_lang']])) {
            $language = $localeArray[$_SESSION['curr_lang']];
        }

        setlocale (LC_ALL,$language);
        //set the .po file
        putenv("LANG=".$language);
        bindtextdomain('messages',$_SERVER['DOCUMENT_ROOT']."locale");
        textdomain("messages");
        bind_textdomain_codeset('messages', 'UTF-8');
        
        $this->locale = $language;

        return $language;
    }        
}

//EOF
?>
