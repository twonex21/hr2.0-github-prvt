<?php
namespace HR\Company;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class ApplicantsAction extends Action implements ActionInterface 
{
    public function perform() {
    	$currentCompanyId = null;
    	$applicants = array();
    	$perPage = 10;
    	 
    	$this->setPageTitle(PT_JOB_APPLICANTS);
    	 
    	if($this->session->isCompanyAuthorized()) {
    		$currentCompanyId = $this->session->getCurrentCompanyId();
    		$applicants = $this->model->getCompanyApplicants($currentCompanyId);
    		$pagesCount = ceil(count($applicants) / $perPage);
    	
    		$this->view->showCompanyApplicants($applicants, $pagesCount);
    	} else {
    		$this->fc->delegateNoAccess();
    	}
    }
}

?>