<?php
namespace HR\Auth;

use HR\Core\ActionInterface;
use HR\Core\Action;

class CompanySignupAction extends Action implements ActionInterface 
{
    public function perform() {
    	// User must be not authorized
    	if(!$this->session->isUserAuthorized() && !$this->session->isCompanyAuthorized()) {	    	    		
    		if($this->request->request->isEmpty()) {
				$this->view->showCompanySignupForm();
    		} else {
    			// Form submitted, handling POST data
    		}	
    	}
    }
}

?>