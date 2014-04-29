<?php
namespace HR\Auth;

use HR\Core\ActionInterface;
use HR\Core\Action;

class SignupAction extends Action implements ActionInterface 
{
    public function perform() {
    	// User must be not authorized
    	if(!$this->session->isUserAuthorized() && !$this->session->isCompanyAuthorized()) {	    	    		
    		$this->view->showSignupPage();	    	
    	}
    }
}

?>