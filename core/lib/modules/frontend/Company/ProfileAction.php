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
    	$companyVacancies = array();    	
    	$maxPageViews = 0;
    	$usersAppliedCount = 0;
    	$isSubscriptionForOpenings = false;
    	$isWorker = false;
    	    	
    	
    	// Getting detail page company id
    	if(!$this->request->query->isNullOrEmpty('cid')) {
    		$companyHash = $this->request->query->get('cid');
    		$companyId = FrontendUtils::hrDecode($companyHash);
    	}
    	
    	// Getting current user or company id    	
    	if($this->session->isUserAuthorized()){ 
    		$currentUserId = $this->session->getCurrentUserId();
    	} else if($this->session->isCompanyAuthorized()){
    		$currentCompanyId = $this->session->getCurrentCompanyId();
    	}
    	
    	// Incrementing page view
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
    	
    	// Benefits
    	$allBenefits = $this->qb->getBenefits();
    	$companyBenefits = $this->qb->getCompanyBenefits($companyId);
    	
    	// Company vacancies
    	$companyVacancies = $this->qb->getCompanyVacancies($companyId, VACANCY_STATUS_ACTIVE);
    	
    	// Maximum pageviews count
    	$maxPageViews = $this->model->getMaxCompanyPageViews();
    	
    	// Users applied count
    	$usersAppliedCount = $this->model->getUsersAppliedCount($companyId);
    	
    	// Is subscribed
    	$isSubscriptionForOpenings = $this->model->isSubscriptionForOpenings($companyId, $currentUserId);
    	
    	// Wants to work here
    	$isWorker = $this->qb->isAlreadyWorker($currentUserId, $companyId);
    	
    	// Setting page title
    	$this->setPageTitle($companyProfile['name']);
    	
    	$this->view->showCompanyProfilePage($companyProfile, $companyOffices, $allBenefits, 
    			                            $companyBenefits, $companyVacancies, $maxPageViews, 
    										$usersAppliedCount, $isSubscriptionForOpenings, $isWorker);
    }       

}

?>