<?php
namespace HR\Main;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\Logger;
use HR\Core\FrontendUtils;

class TestAction extends Action implements ActionInterface 
{
    public function perform() {    	
    	$number = 1;
    	if(!$this->request->query->isNullOrEmpty('id')) {
    		$number = $this->request->query->getInt('id');
    	}
    	
    	//$this->session->destroy();
    	
    	if($this->session->isUserAuthorized()) {
    		var_dump($this->session->getCurrentUser());
    	}
    	
    	if($this->session->isCompanyAuthorized()) {
    		var_dump($this->session->getCurrentCompany());
    	}
    	
    	$this->setPageTitle('HR.am - Home');
    	//$this->session->set('test', 'Here is our test string');
    	    	
    	if($this->request->request->isEmpty()) {
    		// POST is empty
    		$data = $this->model->prepareTest();
    		// Testing rendering
	    	$this->view->testForm();
    	} else {
    		// Testing Query Builder
	    	$data1 = $this->qb->test();
	    	// Testing Frontend Utils
	    	$encodedNumber = FrontendUtils::fcEncode($number);
	    	// Testing Model
	    	
	    	foreach($data as $user) {
	    		// Testing e-mails
	    		if(isset($user['mail'])) {
	    			$this->mailer->test($user);	
	    		}
	    	}  
	    	// Testing logs
	    	$this->logger->append('Just testing...', Logger::LINE);
	
	    	//echo $this->request->request->get('ids[1]', true, 0);
	    	// Testing rendering
	    	$this->view->test($encodedNumber, $data);
    	}
    }
}
//EOF
?>