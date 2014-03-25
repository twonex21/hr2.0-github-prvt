<?php
namespace HR\Main;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\Logger;
use HR\Core\FrontendUtils;

class HomeAction extends Action implements ActionInterface {

	public function __construct() {
		parent::__construct(__NAMESPACE__);
	}
	
    public function perform($reqParameters) {    	
    	$number = 1;
    	if(isset($reqParameters['id'])) {
    		$number = $reqParameters['id'];
    	}
    	
    	// Testing Query Builder
    	$data1 = $this->qb->test();
    	// Testing Frontend Utils
    	$encodedNumber = FrontendUtils::fcEncode($number);
    	// Testing Model
    	$data = $this->model->test();
    	foreach($data as $user) {
    		// Testing e-mails
    		if(isset($user['mail'])) {
    			$this->mailer->test($user);	
    		}
    	}  
    	// Testing logs
    	$this->logger->append('Just testing...', Logger::LINE);
    	$this->logger->write();
    	// Testing rendering
    	$this->view->test($encodedNumber, $data);
    }
}
//EOF
?>