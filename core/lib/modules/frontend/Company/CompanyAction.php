<?php
namespace HR\Company;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class CompanyAction extends Action implements ActionInterface 
{
    public function perform() {
    	
    	$currentCompanyId = $this->session->getCurrentCompanyId();
    	
    	// Collecting data
    	$companyProfile = $this->model->getCompanyProfileById($currentCompanyId);
    	$companyOffices = $this->model->getCompanyOfficesById($currentCompanyId);
    	
    	//Benefits
    	$allBenefits = $this->qb->getBenefits();
    	$companyBenefits = $this->model->getCompanyBenefitsByCompanyId($currentCompanyId);
    	
    	// Setting page title
    	$this->setPageTitle($companyProfile['name']);
    	
    	$this->view->showCompanyPage($companyProfile, $companyOffices, $allBenefits, $companyBenefits);
    }        
}

?>