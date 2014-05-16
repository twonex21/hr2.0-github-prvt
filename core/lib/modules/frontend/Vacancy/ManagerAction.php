<?php
namespace HR\Vacancy;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class ManagerAction extends Action implements ActionInterface 
{
    public function perform() {
    	$currentCompanyId = null;
    	$vacancies = array();
    	$perPage = 10;
    	
    	$this->setPageTitle(PT_VACANCY_MANAGER);
    	
    	if($this->session->isCompanyAuthorized()) {
    		$currentCompanyId = $this->session->getCurrentCompanyId();	
    		$vacancies = $this->qb->getCompanyVacancies($currentCompanyId);
    		$pagesCount = ceil(count($vacancies) / $perPage);
    		
    		$this->view->showVacancyManager($vacancies, $pagesCount);
    	} else {
    		$this->fc->delegateNoAccess();
    	}
    }
}

?>