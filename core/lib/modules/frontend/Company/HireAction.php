<?php
namespace HR\Company;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class HireAction extends Action implements ActionInterface 
{
    public function perform() {
    	$currentCompanyId = 0;
    	
    	// Getting detail page company id
    	$userId = $this->request->request->get('uid');
    	
    	$this->response->jsonPrepare();
    	    	
    	if($this->session->isCompanyAuthorized()){
    		$currentCompanyId = $this->session->getCurrentCompanyId();
    	}
    	
    	if($currentCompanyId !== 0) {
    		if(!$this->qb->isAlreadyHired($userId, $currentCompanyId)) {
	    		$this->model->addHiredUser($userId, $currentCompanyId);
	    		// TODO: Send e-mail to user
	    		// TODO: Change message text
	    		$this->response->jsonSet(JSON_MESSAGE, "Message has been sent to inform the user. Thank you.");
	    		$this->response->jsonSetStatus(SUCCESS);
    		}
    	} else {
    		$this->response->jsonSet(JSON_MESSAGE, "Sorry, some error occured, try again later.");
    		$this->response->jsonSetStatus(FAIL);
    	}
    	
    	// Output
    	$this->response->jsonOutput();
    }       
    
}

?>