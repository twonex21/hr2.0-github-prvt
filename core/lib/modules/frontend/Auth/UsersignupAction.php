<?php
namespace HR\Auth;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class UserSignupAction extends Action implements ActionInterface 
{
    public function perform() {
    	$mail = '';
    	$password = '';
    	$firstName = '';
    	$lastName = '';
    	
    	$currentUserId = 0;
    	$currentUser = array();
    	
    	// User must be not authorized
    	if(!$this->session->isUserAuthorized() && !$this->session->isCompanyAuthorized()) {	    	    		
    		if($this->request->request->isEmpty()) {
				$this->view->showUserSignupForm();
    		} else {
    			// Form submitted, handling POST data	    		
	    		if(!$this->request->request->isNullOrEmpty('email') && FrontendUtils::isEmailAddress($this->request->request->get('email')) && $this->qb->notAlreadyUsed($this->request->request->get('email'))) {
	    			$mail = $this->request->request->get('email');
	    		} else {
	    			return;
	    		}
	    		
	    		if(!$this->request->request->isNullOrEmpty('password') && FrontendUtils::isPasswordLength($this->request->request->get('password'))) {
	    			$password = $this->request->request->get('password');
	    			
	    		} else {
	    			return;
	    		}
	    		
	    		if(!$this->request->request->isNullOrEmpty('firstname') && FrontendUtils::isLatin($this->request->request->get('firstname'))) {
	    			$firstName = $this->request->request->get('firstname');
	    		} else {
	    			return;
	    		}
	    		
    			if(!$this->request->request->isNullOrEmpty('lastname') && FrontendUtils::isLatin($this->request->request->get('lastname'))) {
	    			$lastName = $this->request->request->get('lastname');
	    		} else {
	    			return;
	    		}
	    		
	    		if($this->request->request->isNullOrEmpty('has-agreed')) {
	    			return;
	    		}
	    		
	    		// Handled POST data, now registering user
	    		$currentUserId = $this->model->registerUser($mail, $password, $firstName, $lastName);
	    		$currentUser = $this->qb->getUserSessionDataById($currentUserId);
	    		
	    		// Setting user to session	    		
	    		$this->session->setCurrentUser($currentUser);
	    		
	    		// TODO: Add mail function
	    		//$this->mailer->sendUserWelcomeMessage($mail, $firstName, $lastName);
	    		
	    		$this->fc->redirect('user', 'create');
    		}	
    	}
    }
}

?>