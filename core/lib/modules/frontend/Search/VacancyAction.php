<?php
namespace HR\Search;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class VacancyAction extends Action implements ActionInterface 
{
    public function perform() {  	
    	$searchResults = array();
    	$currentUserId = 0;
    	$currentUser = array();
    	$count = 8;
    	
    	if($this->session->isUserAuthorized()) {
    		$currentUserId = $this->session->getCurrentUserId();
    		$currentUser['education'] = $this->qb->getUserEducation($currentUserId);
			$currentUser['experience'] = $this->qb->getUserExperience($currentUserId);
			$currentUser['languages'] = $this->qb->getUserLanguages($currentUserId);
			$currentUser['skills'] = $this->qb->getUserSkills($currentUserId);
			$currentUser['softSkills'] = $this->qb->getUserSoftSkills($currentUserId); 
    	}
    	
    	$keyword = $this->request->request->get('keyword', '');    	
    	$searchResults = $this->model->getVacancySearchResults($keyword, $count, $currentUser);    	        	
    	
    	if($currentUserId != 0) {			
    		usort($searchResults, function($a, $b) {
    			if($a['weight'] - $b['weight'] != 0) {
    				return $b['weight'] - $a['weight'];
    			}
    			
    			return $b['matching']['total'] - $a['matching']['total'];
    		});		    	    	
    	}    	
    	
    	$this->view->showVacancySearchResults($searchResults);
    }           
}

?>