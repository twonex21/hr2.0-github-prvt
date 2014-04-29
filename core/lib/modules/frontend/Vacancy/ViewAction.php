<?php
namespace HR\Vacancy;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class ViewAction extends Action implements ActionInterface 
{
    public function perform() {
    	$vacancyId = null;
    	$vacancy = array();   
    	$matching = array(); 
    	$currentUserId = null;
    	$canApply = false;
    	$user = array();	
    	$currentUserRole = null;
    	$requiredExperience = 0;

    	if(!$this->request->query->isNullOrEmpty('vid')) {
    		// Taking get parameter
    		$vacancyId = $this->request->query->get('vid');    		
    	}
    	
    	$vacancy['info'] = $this->qb->getVacancyInfo($vacancyId);
    	if(!empty($vacancy['info'])) {			
			$this->setPageTitle($vacancy['info']['title'] . ' at ' . $vacancy['info']['companyName']);
			
			// Getting vacancy data
			$vacancy['education'] = $this->qb->getVacancyEducation($vacancyId);
			$vacancy['experience'] = $this->qb->getVacancyExperience($vacancyId);
			$vacancy['languages'] = $this->qb->getVacancyLanguages($vacancyId);
			$vacancy['skills'] = $this->qb->getVacancySkills($vacancyId);
			$vacancy['softSkills'] = $this->qb->getVacancySoftSkills($vacancyId);
			$vacancy['benefits'] = $this->qb->getVacancyBenefits($vacancyId);
						
			foreach($vacancy['experience'] as $expItem) {
				$requiredExperience += $expItem['years'];
			} 
			
			if($this->session->isUserAuthorized()) {
				$currentUserRole = USER;
				$currentUserId = $this->session->getCurrentUserId();				
				// Calculating matching percentage
				$user['education'] = $this->qb->getUserEducation($currentUserId);
				$user['experience'] = $this->qb->getUserExperience($currentUserId);
				$user['languages'] = $this->qb->getUserLanguages($currentUserId);
				$user['skills'] = $this->qb->getUserSkills($currentUserId);
				$user['softSkills'] = $this->qb->getUserSoftSkills($currentUserId);
				
				$matching = FrontendUtils::calculateMatching($user, $vacancy);				
				$matchingLevels = unserialize(MATCHING_LEVELS);
				foreach($matchingLevels as $threshold => $level) {
					if($matching['skills'] < (int)$threshold) {
						$matching['skillsLevel'] = $level;
						break;
					}
				}
				
				// Check if user is able to apply for the vacancy
				// Has not applied yet and total matching is higher than application threshold
				$canApply = (!$this->qb->hasApplied($vacancyId, $currentUserId) && $matching['total'] > MATCHING_APPLICATION_THRESHOLD);
				
			} elseif($this->session->isCompanyAuthorized()) {
				$currentUserRole = COMPANY;
				$currentUserId = $this->session->getCurrentCompanyId();				
			}
			// Incrementing user view and storing view by specific user
			$this->model->addVacancyView($vacancyId);
			if($currentUserRole != null) {
				$this->model->storeVacancyView($vacancyId, $currentUserRole, $currentUserId);
			}
			
			$this->view->showVacancyPage($vacancy, $matching, $requiredExperience, $canApply);
    	} else {
	    	// Showing not found page
			$this->fc->delegateNotFound();
    	}    	 
    }        
}

?>