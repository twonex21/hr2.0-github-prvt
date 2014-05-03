<?php
namespace HR\Company;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class ProfileAction extends Action implements ActionInterface 
{
    public function perform() {
    	
    	//getting detail page company id
    	$currentCompanyId = $this->request->query->get('cid', 0);    	
    	
    	if(!is_numeric($currentCompanyId)){
    		//TODO redirect to error page;
    		echo "Error with id";
    		exit;
    	}
    	
    	//TODO solve worning  trowing in Session class and do not use @
    	$loggedCompanyId = @$this->session->getCurrentCompanyId();
    	$loggedUserId = @$this->session->getCurrentUserId();
    	
    	//incrementing page view count
    	$this->model->incrementPageViews($currentCompanyId);
    	//logging logged user or company visit to current company detail page
    	if($loggedCompanyId !== 0){
    		$this->model->logCompanyPageView($currentCompanyId, $loggedCompanyId, COMPANY);
    	}
    	if($loggedUserId !== 0){
    		$this->model->logCompanyPageView($currentCompanyId, $loggedUserId, USER);
    	}
    	
    	// Collecting data
    	$companyProfile = $this->model->getCompanyProfileById($currentCompanyId);
    	$companyOffices = $this->model->getCompanyOfficesById($currentCompanyId);
    	
    	//Benefits
    	$allBenefits = $this->qb->getBenefits();
    	$companyBenefits = $this->qb->getCompanyBenefits($currentCompanyId);
    	
    	// Setting page title
    	$this->setPageTitle($companyProfile['name']);
    	
    	$this->view->showCompanyPage($companyProfile, $companyOffices, $allBenefits, $companyBenefits);
    }        
}

?>