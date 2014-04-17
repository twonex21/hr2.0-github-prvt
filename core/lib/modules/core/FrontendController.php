<?php
namespace HR\Core;

require PATH_DIR.'/frontend/configs/const.ini';
require PATH_DIR.'/frontend/messages/properties.ini';

class FrontendController
{	
	const CONTROLLER_KEY = 'controller';
	const ACTION_KEY = 'action';
	const PARAMETERS_KEY = 'parameters';
	const QUERY_KEY = 'query';
	
	private static $querySpecialKeys = array(
		self::CONTROLLER_KEY, 
		self::ACTION_KEY, 
		self::PARAMETERS_KEY, 
		self::QUERY_KEY
	);	
	
	protected $request;
	
	protected $session;
	
	private $locale;
	
	public function __construct() {
		$this->request = new Request();
   		$this->session = new Session();
   		$this->session->start();   		
	}
	
	
	public function getRequest() {
		return $this->request;
	}
	
	
    /**
     * Process a request, calls delegate to handle the request
     */
    public function process() {
    	$controller = NAVIGATE_DEFAULT_CONTROLLER;
        $action = NAVIGATE_DEFAULT_ACTION;
    	$query = null;        
        $parameters = null;

        if($this->request->query->has(self::PARAMETERS_KEY)) {
            $parameters = $this->request->parseParameters($this->request->query->get(self::PARAMETERS_KEY));
        }
        
        foreach($this->request->query->getParameters() as $key => $value) {
            if(!in_array($key, self::$querySpecialKeys)) {
                $parameters[$key] = $value;
            }                        
        }        
        
        if(!$this->request->query->isNullOrEmpty(self::QUERY_KEY) && ($this->request->query->isNullOrEmpty(self::CONTROLLER_KEY) || $this->request->query->isNullOrEmpty(self::ACTION_KEY))) {
        	$directActions = unserialize(DIRECT_ACTIONS);
        	$query = strtolower($this->request->query->get(self::QUERY_KEY));        	          	
        	
        	// Handling short url cases (direct actions)
        	if(array_key_exists($query, $directActions)) {
        		if(is_array($directActions[$query])) {
        			$controller = $directActions[$query][self::CONTROLLER_KEY];
        			$action = $directActions[$query][self::ACTION_KEY];
        			
        			if(isset($directActions[$query][self::PARAMETERS_KEY])) {
        				foreach($directActions[$query][self::PARAMETERS_KEY] as $key => $value) {
        					$parameters[$key] = $value;
        				}
        			}
        			
        		} else {
	        		$controller = $directActions[$query];
	        		$action = $query;
        		}
        	} else {
        		$this->delegateNotFound();
        		return;
        	}
				
        } elseif(!$this->request->query->isNullOrEmpty(self::CONTROLLER_KEY) && !$this->request->query->isNullOrEmpty(self::ACTION_KEY)) {
	        $controller = $this->request->query->get(self::CONTROLLER_KEY);
	        $action = $this->request->query->get(self::ACTION_KEY);
        }                
        
        // Let delegate handle the rest
        $this->delegate($controller, $action, $parameters);
    }

    /**
     * Process a request, load the module and let it handle the rest
     * @param string targetController The controller handling the request
     * @param string targetAction The action requested
     */
    public function delegate($targetController, $targetAction, $queryParams = array(), $objectParams = null) {
        if(!isset($targetController) || !isset($targetAction)) {
            throw new BaseException('Controller', 'No controller or no action');
        }

        // The module that handles our request
        $targetController = ucfirst(strtolower($targetController));
        // The class that will perform the action requested
        $targetAction = ucfirst(strtolower($targetAction));

        // Checking if user has access to the requested page
        $userSecuredActions = unserialize(USER_SECURED_ACTIONS);
        $companySecuredActions = unserialize(COMPANY_SECURED_ACTIONS); 
        $isUserSecured = isset($userSecuredActions[$targetController]) && in_array($targetAction, $userSecuredActions[$targetController]);
        $isCompanySecured = isset($companySecuredActions[$targetController]) && in_array($targetAction, $companySecuredActions[$targetController]);        
        
        if(($isUserSecured && !$this->session->isUserAuthorized()) || ($isCompanySecured && !$this->session->isCompanyAuthorized())) {
        	// Forced attempt to secured page
        	// Showing 'no access' page
        	// TODO: Consider showing login form instead
        	$this->delegateNoAccess();
        	return;
        }
        
        $filepath = LIB_DIR.'modules/frontend/'.$targetController.'/'.$targetAction.'Action.php';
		
        if(file_exists($filepath))
            require $filepath;
        else {
            $this->delegateNotFound();
            return;
        }
        
        // Getting action namespace
        $actionNamespace = 'HR\\' . $targetController;
        
        // Getting action class name
        $actionClass =  $actionNamespace . '\\' . $targetAction . 'Action';
        // Create an instance of action class
        $actionInstance = new $actionClass();
                
        // Registering OOP encapsulated models for global variables   
        $this->request->query->setParameters($queryParams);
        $actionInstance->registerGlobals($this->request, $this->session);
        
        // Registering model, view and mail layers.
        $actionInstance->registerLayers($actionNamespace);

        // Setting some additional parameters        
        if($objectParams != null) {        	
        	$actionInstance->setParameters($objectParams);
        }
        
        if ($this->locale == null) {
            $this->locale = $this->request->selectLocale();
        }
		
        // Call the perform method
        $actionInstance->perform();
        
    }
    
    
    public function delegateNotFound() {
    	$this->delegate('statics', 'page', array('p' => 'not-found'));
    }

    
    public function delegateNoAccess() {
    	$this->delegate('statics', 'page', array('p' => 'no-access'));
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

        $target = sprintf('Location: http://%s/%s/%s/', $this->request->server->get('HTTP_HOST'), $controller, $action);
        header($target);
        exit;
    }       
}

//EOF
?>
