<?php
namespace HR\Vacancy;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class ViewAction extends Action implements ActionInterface 
{
    public function perform() {
    	$userId = 0;

    	if(!$this->request->query->isNullOrEmpty('uid')) {
    		// Taking get parameter
    		$userHash = $this->request->query->get('uid');
    		$userId = FrontendUtils::hrDecode($userHash);
    	} elseif($this->session->isUserAuthorized()) {
    		// User is watching his/her own profile
    		$userId = $this->session->getCurrentUserId();
    	}
    	
    	$userProfile = $this->model->getUserProfileById($userId);
    	if(!empty($userProfile)) {
			$userEducation = $this->model->getUserEducation($userId);
			$userExperience = array_reverse($this->model->getUserExperience($userId));
			$userLanguages = $this->model->getUserLanguages($userId);
			$userSkills = $this->model->getUserSkills($userId);
			$userSoftSkills = $this->model->getUserSoftSkills($userId);	    	
    		// Setting page title
    		$this->setPageTitle($userProfile['fullName']);
    	
    		$this->view->showProfilePage($userProfile, $userEducation, $userExperience, $userLanguages, $userSkills, $userSoftSkills);
    	} else {
	    	// Showing not found page
			$this->fc->delegateNotFound();
    	}
    }        
}

?>