<?php
namespace HR\Vacancy;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class ApplyAction extends Action implements ActionInterface 
{
    public function perform() {
    	$currentUserId = null;
    	$currentUser = null;
    	$vacancyId = null;
    	
    	$vacancy = array();
    	$user = array();
    	$company = array();
    	
    	$this->response->jsonPrepare();
    	
    	if(!$this->request->request->isNullOrEmpty('vacancyId')) {
    		$vacancyId = $this->request->request->getInt('vacancyId');
    		
    		// TODO: Think about foreach loop if all functions keep receiving only id
    		$vacancy['info'] = $this->qb->getVacancyInfo($vacancyId);
    		$vacancy['education'] = $this->qb->getVacancyEducation($vacancyId);
			$vacancy['experience'] = $this->qb->getVacancyExperience($vacancyId);
			$vacancy['languages'] = $this->qb->getVacancyLanguages($vacancyId);
			$vacancy['skills'] = $this->qb->getVacancySkills($vacancyId);
			$vacancy['softSkills'] = $this->qb->getVacancySoftSkills($vacancyId);
    		
    		// Getting authorized user
    		$currentUserId = $this->session->getCurrentUserId();
    		$currentUser = $this->session->getCurrentUser();
    		
    		$user['education'] = $this->qb->getUserEducation($currentUserId);
    		$user['experience'] = $this->qb->getUserExperience($currentUserId);
    		$user['languages'] = $this->qb->getUserLanguages($currentUserId);
    		$user['skills'] = $this->qb->getUserSkills($currentUserId);
    		$user['softSkills'] = $this->qb->getUserSoftSkills($currentUserId);
    		
    		// Calculating matching percentage
    		$matching = FrontendUtils::calculateMatching($user, $vacancy);
    		
    		if(!$this->qb->hasApplied($vacancyId, $currentUserId) && $matching['total'] > MATCHING_APPLICATION_THRESHOLD) {
    			$this->qb->applyToVacancy($vacancyId, $currentUserId);
    			
    			// TODO: Finalize e-mail sending
    			// Sending e-mail to company
    			$this->mailer->sendApplicationMessage($vacancy['info'], $currentUser['fullName'], FrontendUtils::getResumeFilePath($currentUser['resumeKey']));
    			
    			$this->setMessage(MSG_TYPE_SUCCESS);
    			
    			$this->response->jsonSetStatus(SUCCESS);
    		}
    	}
    	
    	// Output
    	$this->response->jsonOutput();
    }
}

?>