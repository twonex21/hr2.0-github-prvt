<?php
namespace HR\Company;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class CreateAction extends Action implements ActionInterface 
{
    public function perform() {
    	$currentCompanyId = null;
    	$companyProfile = array();
    	$companyOffices = array();
    	$allBenefits = array();
    	$companyBenefits = array();
    	$tmpPictureKey = '';
    	$pictureKey = '';
    	$companyTitle = '';
    	$companyAdditionalInfo = '';
    	$companyPhone = '';
    	$companyEmail = '';
    	$companyLinkedIn = '';
    	$companyFacebook = '';
    	$companyTwitter = '';
    	$subscribeForNewVacancies = 0;
    	$subscribeForNews = 0;
    	$companyEmployeesCount = 0;
    	$showAmountOfViews = 0;
    	$showAmountUsersApplied = 0;
    	
    	// Setting page title
    	$this->setPageTitle(PT_EDIT_COMPANY_PROFILE);
    	
    	$currentCompanyId = $this->session->getCurrentCompanyId();

    	if($this->request->request->isEmpty()) {    		
    		// POST is empty, no input parameters yet
    		
    		// Collecting data if it exists and passing to template
    		$companyProfile = $this->model->getCompanyProfileById($currentCompanyId);
    		$companyOffices = $this->model->getCompanyOfficesById($currentCompanyId);
    		
    		//Benefits
    		$allBenefits = $this->qb->getBenefits();
    		$companyBenefits = $this->qb->getCompanyBenefits($currentCompanyId);

    		$this->view->showCreateCompanyProfilePage($companyProfile, $companyOffices, $allBenefits, $companyBenefits);
    	} else {
    		
    		// Handling form input    		    		
    		if(!$this->request->request->isNullOrEmpty('temp-picture')) {
    			// Stripping random 4 character prefix
    			$tmpPictureKey = $this->request->request->get('temp-picture');
    			 
    			// Moving picture to company folder
    			$picturePath = FrontendUtils::saveTemporaryFile($tmpPictureKey, COMPANY);
    			if($picturePath !== false) {
    				$pictureKey = $tmpPictureKey;
    					
    				// Saving cropped version as well
    				$fileNameParts = explode('.', $picturePath);
    				$extension = strtolower(end($fileNameParts));
    				$croppedPath = sprintf(COMPANY_SQUARE_PICTURE_PATH, $pictureKey, $extension);
    				FrontendUtils::cropAndSaveImage($picturePath, $croppedPath, PICTURE_CROP_DIMENSION, PICTURE_CROP_DIMENSION, $extension);
    			}
    		}
    		
    		if(!$this->request->request->isNullOrEmpty('comp-title')) {
    			$companyTitle = $this->request->request->get('comp-title');
    		} else {
    			return;
    		}
    		
    		if(!$this->request->request->isNullOrEmpty('comp-ad-info')) {
    			$companyAdditionalInfo = $this->request->request->get('comp-ad-info');
    		}
    		
    		$companyOffices = $this->request->request->get('comp-offices', array());
    		foreach ($companyOffices as $key => $companyOffice){
    			if(empty($companyOffice) || !FrontendUtils::isLatin($companyOffice)) {
    				unset($companyOffices[$key]);
    			}
    		}
    		    		
    		if(!$this->request->request->isNullOrEmpty('comp-phone')) {
    			$companyPhone = $this->request->request->get('comp-phone');
    		} else {
    			return;
    		}
    		
    		if(!$this->request->request->isNullOrEmpty('comp-email') && FrontendUtils::isEmailAddress($this->request->request->get('comp-email'))) {
    			$companyEmail = $this->request->request->get('comp-email');
    		} else {
    			return;
    		}
    		
    		if(!$this->request->request->isNullOrEmpty('comp-linked') && FrontendUtils::isLinkedIn($this->request->request->get('comp-linked'))) {
    			$companyLinkedIn = $this->request->request->get('comp-linked');
    		}
    		
    		if(!$this->request->request->isNullOrEmpty('comp-face') && FrontendUtils::isFacebook($this->request->request->get('comp-face'))) {
    			$companyFacebook = $this->request->request->get('comp-face');
    		}

    		if(!$this->request->request->isNullOrEmpty('comp-twitter') && FrontendUtils::isTwitter($this->request->request->get('comp-twitter'))) {
    			$companyTwitter = $this->request->request->get('comp-twitter');
    		}
    		
    		if($this->request->request->get('subscribe-for-new-vacancies') == 1) {
    			$subscribeForNewVacancies = 1;
    		}
    		
    		if($this->request->request->get('subscribe-for-news') == 1) {
    			$subscribeForNews = 1;
    		}
    		
    		if($this->request->request->get('comp-emp-count') !== "") {
    			$companyEmployeesCount = $this->request->request->get('comp-emp-count');
    		}
    		
    		if($this->request->request->get('show-amount-of-views') == 1) {
    			$showAmountOfViews = $this->request->request->get('show-amount-of-views');
    		}
    		
    		if($this->request->request->get('show-amount-users-applied') == 1) {
    			$showAmountUsersApplied = $this->request->request->get('show-amount-users-applied');
    		}
    		
    		$companyBenefits = $this->request->request->get('comp-benefits', array());
    		
    		//Saving values to db
    		$this->model->updateCompanyInfo($currentCompanyId, 
    				                        $companyTitle, 
    				                        $companyAdditionalInfo, 
    				                        $companyPhone,
    				                        $companyEmail, 
    				                        $companyLinkedIn, 
    				                        $companyFacebook,
    				                        $companyTwitter,
    										$pictureKey,
						    				$subscribeForNewVacancies, 
						    				$subscribeForNews,
						    				$companyEmployeesCount,
						    				$showAmountOfViews,
						    				$showAmountUsersApplied);
    		
    		$this->model->updateCompanyOffices($currentCompanyId, $companyOffices);
    		
    		$this->model->updateCompanyBenefits($currentCompanyId, $companyBenefits);
    		
    		$this->fc->redirect('company', 'profile', 'cid/' . FrontendUtils::hrEncode($currentCompanyId) . '/t/');
    		
    	}
    	
    }
}

?>