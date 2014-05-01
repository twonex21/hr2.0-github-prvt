<?php
namespace HR\Company;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class CompanyAction extends Action implements ActionInterface 
{
    public function perform() {
    	
    	//getting ids 
    	//TODO get from url
    	$currentCompanyId = $this->session->getCurrentCompanyId();
    	//$currentUserId = $this->session->getCurrentUserId();
    	
    	//incrementing page view count
    	$this->model->incrementPageViews($currentCompanyId);
    	//TODO implement logging of visiter id to  hr_company_page_view table
    	//$this->model->incrementPageViews($currentCompanyId);
    	
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