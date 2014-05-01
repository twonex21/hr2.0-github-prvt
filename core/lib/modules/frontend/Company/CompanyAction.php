<?php
namespace HR\Company;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class CompanyAction extends Action implements ActionInterface 
{
    public function perform() {
    	
    	$currentCompanyId = $this->session->getCurrentCompanyId();
    	
    	// Collecting data if it exists and passing to template
    	$companyProfile = $this->model->getCompanyProfileById($currentCompanyId);
    	$companyOffices = $this->model->getCompanyOfficesById($currentCompanyId);
    	
    	//Benefits
    	$allBenefits = $this->qb->getBenefits();
    	$companyBenefits = $this->model->getCompanyBenefitsByCompanyId($currentCompanyId);
    	
    	$this->view->showCompanyPage($companyProfile, $companyOffices, $allBenefits, $companyBenefits);
    }        
}

?>