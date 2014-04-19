<?php
namespace HR\Core;

class Action
{
	
	protected $qb;
	protected $logger;
	
	protected $fc;
	protected $request;
	protected $response;
	protected $session;
	
	protected $namespace;
	protected $controller;
	protected $action;
	
	protected $model;
	protected $view;
	protected $mailer;
	
	protected $parameters;
	protected $pageTitle;		
	
   	public function __construct($fc) {
   		$this->initialize($fc);   		
   	}
   	
   	private function initialize($fc) {
   		$this->namespace = $fc->getNamespace();
   		$this->controller = $fc->getController();
   		$this->action = $fc->getAction();
   		
   		// Frontend controller instance to control flow
   		$this->fc = $fc;
   		
   		// Session, request, response handles   		
   		$this->session = &$fc->getSession();	
   		$this->request = &$fc->getRequest();
   		$this->response = new Response();
   	}
   	
   	
   	public function registerLayers() {
   		$this->qb = new QueryBuilder();
		$this->mailer = new SendMail($this->namespace);				
   		$this->logger = Logger::getInstance();
   		
   		// Model and View layers
		$modelClass = $this->namespace . '\\' . $this->controller . 'Model';
		$viewClass = $this->namespace . '\\' . $this->controller . 'View';
		
		if(class_exists($modelClass)) {
			$this->model = new $modelClass();
			$this->model->qb = new QueryBuilder();
		}
		
		if(class_exists($viewClass)) {
			$this->view = new $viewClass($this->namespace, $this->session);
		}   		
   	}
   	
   	
   	public function setParameters($parameters) {
   		$this->parameters = $parameters;
   	}
   	
   	
   	protected function setPageTitle($title) {
   		$this->view->assign('_PAGE_TITLE', 'HR.am - ' . $title);
   	}
   	
   	
   	protected function setMessage($type, $text = '', $isFlash = true) {
   		$text = ($text !== '') ? $text : constant('MSG_' . strtoupper($this->controller) . '_' . strtoupper($this->action) . '_' . strtoupper($type));
   		$this->session->setMessage($type, $text, $isFlash);
   	}
}

?>
