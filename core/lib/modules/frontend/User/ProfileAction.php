<?php
namespace HR\User;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class ProfileAction extends Action implements ActionInterface 
{
    public function perform() {
    	$userId = null;
    	    	
    	if(!$this->request->query->isNullOrEmpty('uid')) {
    		// Taking get parameter
    		$userHash = $this->request->query->get('uid');
    		$userId = FrontendUtils::hrDecode($userHash);
    	} elseif($this->session->isUserAuthorized()) {
    		// User is watching his/her own profile
    		$userId = $this->session->getCurrentUserId();
    	}

    	if($userId !== null) {
	    	$userProfile = $this->model->getUserProfileById($userId);
			$userEducation = $this->model->getUserEducation($userId);
			$userExperience = array_reverse($this->model->getUserExperience($userId));
			$userLanguages = $this->model->getUserLanguages($userId);
			$userSkills = $this->model->getUserSkills($userId);
			$userSoftSkills = $this->model->getUserSoftSkills($userId);
	    	
	    	// Setting page title
	    	$this->setPageTitle($userProfile['fullName']);
	    	
	    	$this->view->showProfilePage($userProfile, $userEducation, $userExperience, $userLanguages, $userSkills, $userSoftSkills);
    	}
    }
    
    
}

?>