<?php
namespace HR\Company;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class SubscribeforopeningsAction extends Action implements ActionInterface 
{
    public function perform() {
    	
    	//getting detail page company id
    	$companyId = $this->request->query->get('cid');
    	$this->response->jsonPrepare();
    	
    	$currentUserId = 0;
    	if($this->session->isUserAuthorized()){
    		$currentUserId = $this->session->getCurrentUserId();
    	}
    	
    	if($currentUserId !== 0){
    		//TODO probably use false output for displaying already subscribed message ?
    		$this->model->addSubscriptionForOpenings($companyId, $currentUserId);
    		$this->response->jsonSetStatus(SUCCESS);
    	}else{
    		$this->response->jsonSetStatus(FAIL);
    	}
    	
    	// Output
    	$this->response->jsonOutput();
    }       
    
}

?>