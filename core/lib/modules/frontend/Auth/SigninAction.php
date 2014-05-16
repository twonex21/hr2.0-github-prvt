<?php
namespace HR\Auth;

use Facebook\FacebookSession as FacebookSession;
use Facebook\FacebookRedirectLoginHelper as FacebookRedirectLoginHelper;

use HR\Core\ActionInterface;
use HR\Core\Action;

class SigninAction extends Action implements ActionInterface 
{
    public function perform() {
    	$mail = '';
    	$password = '';
    	
    	
    	// User must be not authorized
    	if(!$this->session->isUserAuthorized() && !$this->session->isCompanyAuthorized()) {
    		if($this->request->request->isEmpty()) {
    			// POST is empty
    			
    			// Generating FB Login url
    			FacebookSession::setDefaultApplication(FB_APP_ID, FB_APP_SECRET);
    			$fbHelper = new FacebookRedirectLoginHelper('http://local.hr.am/auth/fbconnect/');
    			$fbUrl = $fbHelper->getLoginUrl(array('email', 'user_birthday', 'user_location'), 'popup');
    			
    			// Showing signin modal
    			$this->view->showSigninPage($fbUrl);
    		} else {
    			$this->response->jsonPrepare();
    			
    			if(!$this->request->request->isNullOrEmpty('mail')) {
    				$mail = $this->request->request->get('mail');
    			} else {
    				return;
    			}
    			
    			if(!$this->request->request->isNullOrEmpty('password')) {
    				$password = $this->request->request->get('password');
    			} else {
    				return;
    			}
    			
    			$entity = $this->model->getUserByCredentials($mail, $password);
    			// Checking if user exists
    			if(!empty($entity)) {
    				if($entity['type'] == USER) {
    					// Authorizing user
    					$currentUser = $this->qb->getUserSessionDataById($entity['ID']);
    					$this->session->setCurrentUser($currentUser);
    					if(!$this->qb->isUserProfileComplete($entity['ID'])) {
    						// User Profile is not complete, redirecting to create page
    						$this->response->jsonSet('redirectUrl', '/user/create/');
    					}
    					// Sending success status
    					$this->response->jsonSetStatus(SUCCESS);
    				} else if($entity['type'] == COMPANY) {
    					// Authorizing company
    					$currentCompany = $this->qb->getCompanySessionDataById($entity['ID']);
    					$this->session->setCurrentCompany($currentCompany);
    					if(!$this->qb->isCompanyProfileComplete($entity['ID'])) {
    						// Company Profile is not complete, redirecting to create page
    						$this->response->jsonSet('redirectUrl', '/company/create/');
    					}
    					// Sending success status
    					$this->response->jsonSetStatus(SUCCESS);
    				}
    			} else {
    				$this->response->jsonSet(JSON_MESSAGE, 'Wrong e-mail or password');
    			}
    			
    			// Output
    			$this->response->jsonOutput();
    		}	    	
    	}
    }
}

?>