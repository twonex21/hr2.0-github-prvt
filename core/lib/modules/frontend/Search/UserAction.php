<?php
namespace HR\Search;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class UserAction extends Action implements ActionInterface 
{
    public function perform() {  	
    	$searchResults = array();
    	$currentCompanyId = 0;    	
    	$vacancyIds = array();
    	$vacancies = array();
    	$count = 8;
    	
    	$currentCompanyId = $this->session->getCurrentCompanyId();
    	// Getting active company vacancies
		$vacancyIds = $this->qb->getCompanyVacancyIds($currentCompanyId);
    	foreach($vacancyIds as $idx => $vacancyId) {
	    	// TODO: Think about getting all user or vacancy data in one call
	    	$vacancies[$idx]['title'] = $this->qb->getVacancyTitle($vacancyId);
	    	$vacancies[$idx]['education'] = $this->qb->getVacancyEducation($vacancyId);
	    	$vacancies[$idx]['experience'] = $this->qb->getVacancyExperience($vacancyId);
	    	$vacancies[$idx]['languages'] = $this->qb->getVacancyLanguages($vacancyId);
			$vacancies[$idx]['skills'] = $this->qb->getVacancySkills($vacancyId);
			$vacancies[$idx]['softSkills'] = $this->qb->getVacancySoftSkills($vacancyId);
		}
    	
    	$keyword = $this->request->request->get('keyword', '');    	
    	$searchResults = $this->model->getUserSearchResults($keyword, $count, $vacancies);    	        	
		
    	usort($searchResults, function($a, $b) {
    		if($a['weight'] - $b['weight'] != 0) {
    			return $b['weight'] - $a['weight'];
    		}
    		
    		return $b['matching']['total'] - $a['matching']['total'];
		});    	    
    	
    	$this->view->showUserSearchResults($searchResults);
    }           
}

?>