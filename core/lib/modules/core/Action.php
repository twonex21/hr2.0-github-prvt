<?php
namespace HR\Core;

class Action extends FrontendController 
{
	protected $mailer = null;
	protected $qb = null;
	protected $model = null;
	protected $view = null;
	protected $logger = null;
	
   	public function __construct($actionNamespace = '') {
   		$this->mailer = new SendMail($actionNamespace);
   		$this->qb = new QueryBuilder();
   		$this->logger = Logger::getInstance();
   		
   		if($actionNamespace != '') {
   			$parts = explode('\\', $actionNamespace);    	
    		$controller = $parts[count($parts) - 1];
    		$modelClass = $actionNamespace . '\\' . $controller . 'Model';
    		$viewClass = $actionNamespace . '\\' . $controller . 'View';
    		
   			if(class_exists($modelClass)) {
   				$this->model = new $modelClass();
   			}
   			
   			if(class_exists($viewClass)) {
   				$this->view = new $viewClass($actionNamespace);
   			}
   		}
   	}   	
}
//EOF
?>
