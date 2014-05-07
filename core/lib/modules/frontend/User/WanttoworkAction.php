<?php
namespace HR\User;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class WanttoworkAction extends Action implements ActionInterface 
{
    public function perform() {
    	
    	// Getting detail page company id
    	$companyId = $this->request->request->get('cid');
    	
    	$this->response->jsonPrepare();
    	
    	$currentUserId = 0;
    	if($this->session->isUserAuthorized()){
    		$currentUserId = $this->session->getCurrentUserId();
    	}
    	
    	if($currentUserId !== 0) {
    		if(!$this->qb->isAlreadyWorker($currentUserId, $companyId)) {
	    		$this->model->addWantToWork($currentUserId, $companyId);
	    		// TODO: Change message text
	    		$this->response->jsonSet(JSON_MESSAGE, "Your request has been successfully sent");
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