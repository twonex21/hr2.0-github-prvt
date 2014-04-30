<?php
namespace HR\Auth;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class CompanySignupAction extends Action implements ActionInterface 
{
    public function perform() {
    	$name = '';
    	$mail = '';
    	$password = '';
    	$phone = '';
    	$person = '';
    	
    	$currentCompanyId = 0;
    	$currentCompany = array();
    	 
    	// User must be not authorized
    	if(!$this->session->isUserAuthorized() && !$this->session->isCompanyAuthorized()) {	    	    		
    		if($this->request->request->isEmpty()) {
				$this->view->showCompanySignupForm();
    		} else {
    			// Form submitted, handling POST data
    			if(!$this->request->request->isNullOrEmpty('name')) {
    				$name = $this->request->request->get('name');
    			} else {
    				return;
    			}
    			 
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
    			 
    			if(!$this->request->request->isNullOrEmpty('phone') && FrontendUtils::isPhoneNumber($this->request->request->get('phone'))) {
    				$phone = $this->request->request->get('phone');
    			} else {
    				return;
    			}
    			 
    			if(!$this->request->request->isNullOrEmpty('person') && FrontendUtils::isLatin($this->request->request->get('person'))) {
    				$person = $this->request->request->get('person');
    			} else {
    				return;
    			}
    			 
    			if($this->request->request->isNullOrEmpty('has-agreed')) {
    				return;
    			}
    			 
    			// Handled POST data, now registering user
    			$currentCompanyId = $this->model->registerCompany($name, $mail, $password, $phone, $person);
    			$currentCompany = $this->qb->getCompanySessionDataById($currentCompanyId);
    			 
    			// Setting user to session
    			$this->session->setCurrentCompany($currentCompany);
    			 
    			// TODO: Add mail function
    			//$this->mailer->sendCompanyWelcomeMessage($mail, $name);
    			 
    			$this->fc->redirect('company', 'create');
    		}	
    	}
    }
}

?>