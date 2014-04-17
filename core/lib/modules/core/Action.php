<?php
namespace HR\Core;

use HR\Core\Session;

class Action extends FrontendController 
{
	
	protected $qb;
	protected $logger;
	
	protected $request;
	protected $response;
	protected $session;
	
	protected $model;
	protected $view;
	protected $mailer;
	
	protected $parameters;
	protected $pageTitle;
	
   	public function __construct() {   		
   		$this->qb = new QueryBuilder();
   		$this->logger = Logger::getInstance();   		
   	}

   	
   	public function registerGlobals($request, $session) {
   		$this->request = &$request;   		
   		$this->session = &$session;
   	}
   	
   	
   	public function registerLayers($actionNamespace) {
   		$parts = explode('\\', $actionNamespace);    	
		$controller = $parts[count($parts) - 1];
		$modelClass = $actionNamespace . '\\' . $controller . 'Model';
		$viewClass = $actionNamespace . '\\' . $controller . 'View';
		
		if(class_exists($modelClass)) {
			$this->model = new $modelClass();
			$this->model->qb = new QueryBuilder();
		}
		
		if(class_exists($viewClass)) {
			$attributes = &$this->session->getAttributes();   				
			$this->view = new $viewClass($actionNamespace, $attributes);
		}
		
		$this->response = new Response();
		$this->mailer = new SendMail($actionNamespace);
   	}
   	
   	
   	public function setPageTitle($title) {
   		$this->view->assign('_PAGE_TITLE', 'HR.am - ' . $title);
   	}
   	
   	
   	public function setParameters($parameters) {
   		$this->parameters = $parameters;
   	}
}
//EOF
?>
