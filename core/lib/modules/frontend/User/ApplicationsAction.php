<?php
namespace HR\User;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class ApplicationsAction extends Action implements ActionInterface 
{
    public function perform() {
    	$currentUserId = null;
    	$currentUser = array();
    	$applications = array();
    	$perPage = 10;
    	 
    	$this->setPageTitle(PT_JOB_APPLICATIONS);
    	 
    	if($this->session->isUserAuthorized()) {
    		$currentUserId = $this->session->getCurrentUserId();    		
    		// TODO: Think about getting all user or vacancy data in one call
    		$currentUser['education'] = $this->qb->getUserEducation($currentUserId);
    		$currentUser['experience'] = $this->qb->getUserExperience($currentUserId);
    		$currentUser['languages'] = $this->qb->getUserLanguages($currentUserId);
    		$currentUser['skills'] = $this->qb->getUserSkills($currentUserId);
    		$currentUser['softSkills'] = $this->qb->getUserSoftSkills($currentUserId);
    		
    		$applications = $this->model->getUserApplications($currentUserId, $currentUser);
    		$pagesCount = ceil(count($applications) / $perPage);
    	
    		$this->view->showUserApplications($applications, $pagesCount);
    	} else {
    		$this->fc->delegateNoAccess();
    	}
    }
}

?>