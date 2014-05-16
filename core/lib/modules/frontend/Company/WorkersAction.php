<?php
namespace HR\Company;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class WorkersAction extends Action implements ActionInterface 
{
    public function perform() {
    	$currentCompanyId = 0;
    	$workers = array();
    	$vacancyIds = array();
    	$vacancies = array();
    	$perPage = 10;
    	 
    	$this->setPageTitle(PT_COMPANY_WORKERS);
    	 
    	if($this->session->isCompanyAuthorized()) {
    		$currentCompanyId = $this->session->getCurrentCompanyId();
    		
    		$vacancyIds = $this->qb->getCompanyVacancyIds($currentCompanyId);
    		foreach($vacancyIds as $idx => $vacancyId) {
    			// TODO: Think about getting all user or vacancy data in one call
    			$vacancies[$idx]['vacancyId'] = $vacancyId;
    			$vacancies[$idx]['title'] = $this->qb->getVacancyTitle($vacancyId);
    			$vacancies[$idx]['education'] = $this->qb->getVacancyEducation($vacancyId);
    			$vacancies[$idx]['experience'] = $this->qb->getVacancyExperience($vacancyId);
    			$vacancies[$idx]['languages'] = $this->qb->getVacancyLanguages($vacancyId);
    			$vacancies[$idx]['skills'] = $this->qb->getVacancySkills($vacancyId);
    			$vacancies[$idx]['softSkills'] = $this->qb->getVacancySoftSkills($vacancyId);
    		}
    		
    		$workers = $this->model->getCompanyWorkers($currentCompanyId, $vacancies);
    		$pagesCount = ceil(count($workers) / $perPage);
    	
    		$this->view->showCompanyWorkers($workers, $pagesCount);
    	} else {
    		$this->fc->delegateNoAccess();
    	}
    }
}

?>