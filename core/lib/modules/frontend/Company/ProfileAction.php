<?php
namespace HR\Company;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class ProfileAction extends Action implements ActionInterface 
{
    public function perform() {
    	
    	//getting detail page company id

    	$companyId = $this->request->query->get('cid', 0);
    	
    	//getting current user or company id
    	$currentCompanyId = $currentUserId = 0;
    	if($this->session->isUserAuthorized()){ 
    		$currentUserId = $this->session->getCurrentUserId();
    	}
    	if($this->session->isCompanyAuthorized()){
    		$currentCompanyId = $this->session->getCurrentCompanyId();
    	}
    	
    	//incrementing page view count
    	$this->model->incrementPageViews($companyId);
    	//logging logged user or company visit to current company detail page
    	if($currentCompanyId !== 0){
    		$this->model->logCompanyPageView($companyId, $currentCompanyId, COMPANY);
    	}
    	if($currentUserId !== 0){
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
    	$maxPageViews = $this->model->getMaxPageViews();
    	
    	//users applyed count
    	$usersApplyedCount = $this->model->getUsersApplyedCount($companyId);
    	
    	//is subscribed
    	$isSubscriptionForOpenings = $this->model->isSubscriptionForOpenings($companyId, $currentUserId);
    	
    	// Setting page title
    	$this->setPageTitle($companyProfile['name']);
    	
    	$this->view->showCompanyProfilePage($companyProfile, $companyOffices, $allBenefits, 
    			                            $companyBenefits, $maxPageViews, $usersApplyedCount,
    			                            $isSubscriptionForOpenings);
    }       

}

?>