<?php
namespace HR\Company;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class ProfileAction extends Action implements ActionInterface 
{
    public function perform() {
    	$companyId = null;
    	$currentCompanyId = $currentUserId = 0;
    	$companyOffices = array();
    	$allBenefits = array();
    	$companyBenefits = array();
    	$maxPageViews = 0;
    	$usersAppliedCount = 0;
    	
    	
    	//getting detail page company id
    	if(!$this->request->query->isNullOrEmpty('cid')) {
    		$companyHash = $this->request->query->get('cid');
    		$companyId = FrontendUtils::hrDecode($companyHash);
    	}
    	
    	//getting current user or company id    	
    	if($this->session->isUserAuthorized()){ 
    		$currentUserId = $this->session->getCurrentUserId();
    	} else if($this->session->isCompanyAuthorized()){
    		$currentCompanyId = $this->session->getCurrentCompanyId();
    	}
    	
    	//incrementing page view count
    	$this->model->incrementPageViews($companyId);
    	//logging logged user or company visit to current company detail page
    	if($currentCompanyId !== 0){
    		$this->model->logCompanyPageView($companyId, $currentCompanyId, COMPANY);
    	} else if($currentUserId !== 0){
    		$this->model->logCompanyPageView($companyId, $currentUserId, USER);
    	}
    	
    	// Collecting data
    	$companyProfile = $this->model->getCompanyProfileById($companyId);
    	if(empty($companyProfile)){
    		$this->fc->delegateNotFound();
    	}
    	$companyOffices = $this->model->getCompanyOfficesById($companyId);
    	
    	//Benefits
    	$allBenefits = $this->qb->getBenefits();

    	$companyBenefits = $this->qb->getCompanyBenefits($companyId);
    	
    	//Maximum pageviews count
    	$maxPageViews = $this->model->getMaxCompanyPageViews();
    	
    	//users applied count
    	$usersAppliedCount = $this->model->getUsersAppliedCount($companyId);
    	
    	// Setting page title
    	$this->setPageTitle($companyProfile['name']);
    	
    	$this->view->showCompanyProfilePage($companyProfile, $companyOffices, $allBenefits, 
    			                            $companyBenefits, $maxPageViews, $usersAppliedCount);
    }       

    
     public function subscribeForOpenings(){
     	echo 55;
     }
}

?>