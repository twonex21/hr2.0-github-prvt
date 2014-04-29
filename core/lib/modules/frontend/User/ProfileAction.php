<?php
namespace HR\User;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class ProfileAction extends Action implements ActionInterface 
{
    public function perform() {
    	$userId = null;
    	$user = array();

    	if(!$this->request->query->isNullOrEmpty('uid')) {
    		// Taking get parameter
    		$userHash = $this->request->query->get('uid');
    		$userId = FrontendUtils::hrDecode($userHash);
    	} elseif($this->session->isUserAuthorized()) {
    		// User is watching his/her own profile
    		$userId = $this->session->getCurrentUserId();
    	}
    	
    	$user['profile'] = $this->qb->getUserProfileById($userId);
    	if(!empty($user['profile'])) {
			$user['education'] = $this->qb->getUserEducation($userId);
			$user['experience'] = $this->qb->getUserExperience($userId);
			$user['languages'] = $this->qb->getUserLanguages($userId);
			$user['skills'] = $this->qb->getUserSkills($userId);
			$user['softSkills'] = $this->qb->getUserSoftSkills($userId);	    	
    		// Setting page title
    		$this->setPageTitle($user['profile']['fullName']);
    	
    		$this->view->showProfilePage($user);
    	} else {
	    	// Showing not found page
			$this->fc->delegateNotFound();
    	}
    }        
}

?>