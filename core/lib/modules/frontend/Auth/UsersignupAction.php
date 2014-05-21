<?php
namespace HR\Auth;

use Facebook\FacebookSession as FacebookSession;
use Facebook\FacebookRedirectLoginHelper as FacebookRedirectLoginHelper;

use HappyR\LinkedIn\LinkedIn;

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
    			// Generating FB Login url
    			FacebookSession::setDefaultApplication(FB_APP_ID, FB_APP_SECRET);
    			$fbHelper = new FacebookRedirectLoginHelper('http://local.hr.am/auth/fbconnect/');
    			$fbUrl = $fbHelper->getLoginUrl(array('email', 'user_birthday', 'user_location'), 'popup');
    			
    			$linkedIn = new LinkedIn(LI_APP_ID, LI_APP_SECRET);
    			$linkedIn->setRedirectUrl('http://hr.dev/auth/liconnect/');
    			$linkedInUrl = $linkedIn->getLoginUrl();
    			
				$this->view->showUserSignupForm($fbUrl, $linkedInUrl);
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