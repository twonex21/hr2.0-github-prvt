<?php
namespace HR\Company;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class CreateAction extends Action implements ActionInterface 
{
    public function perform() {

    	
    	// Setting page title
    	$this->setPageTitle('Company Page Creaton');
    	
    	$currentCompanyId = $this->session->getCurrentCompanyId();

    	if($this->request->request->isEmpty()) {
    		
    		// POST is empty, no input parameters yet
    		
    		//collecting data if it exists and passing to template
    		$companyProfile = $this->model->getCompanyProfileById($currentCompanyId);
    		$companyOffices = $this->model->getCompanyOfficesByCompanyId($currentCompanyId);
    		
    		//Benefits
    		$allBenefits = $this->model->getAllBenefits();
    		$companyBenefits = $this->model->getCompanyBenefitsByCompanyId($currentCompanyId);

    		$this->view->showCreateCompanyProfilePage($companyProfile, $companyOffices, $allBenefits, $companyBenefits);
    	} else {
    		
    		// Handling form input
    		
    		$pictureKey = "";
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
    		$companyTitle = "";
    		if(!$this->request->request->isNullOrEmpty('comp_title') && FrontendUtils::isLatin($this->request->request->get('comp_title'))) {
    			$companyTitle = $this->request->request->get('comp_title');
    		}
    		$companyAdditionalInfo="";
    		if(!$this->request->request->isNullOrEmpty('comp_ad_info') && FrontendUtils::isLatin($this->request->request->get('comp_ad_info'))) {
    			$companyAdditionalInfo = $this->request->request->get('comp_ad_info');
    		}
    		
    		$companyOffices = $this->request->request->get('comp_offices');
    		foreach ($companyOffices as $key => $companyOffice){
    			if(empty($companyOffice) || !FrontendUtils::isLatin($companyOffice)) {
    				unset($companyOffices[$key]);
    			}
    		}
    		
    		$companyPhone="";
    		if(!$this->request->request->isNullOrEmpty('comp_phone')) {
    			$companyPhone = $this->request->request->get('comp_phone');
    		}
    		$companyEmail="";
    		if(!$this->request->request->isNullOrEmpty('comp_email') && FrontendUtils::isEmailAddress($this->request->request->get('comp_email'))) {
    			$companyEmail = $this->request->request->get('comp_email');
    		}
    		$companyLinkedIn="";
    		if(!$this->request->request->isNullOrEmpty('comp_linked') && FrontendUtils::isLinkedIn($this->request->request->get('comp_linked'))) {
    			$companyLinkedIn = $this->request->request->get('comp_linked');
    		}
    		$companyFacebook="";
    		if(!$this->request->request->isNullOrEmpty('comp_face') && FrontendUtils::isFacebook($this->request->request->get('comp_face'))) {
    			$companyFacebook = $this->request->request->get('comp_face');
    		}
    		$companyTwitter="";
    		if(!$this->request->request->isNullOrEmpty('comp_twitter') && FrontendUtils::isTwitter($this->request->request->get('comp_twitter'))) {
    			$companyTwitter = $this->request->request->get('comp_twitter');
    		}
    		$subscribeForNewVacancies=0;
    		if($this->request->request->get('subscribe_for_new_vacancies') == 1) {
    			$subscribeForNewVacancies = 1;
    		}
    		$subscribeForNews=0;
    		if($this->request->request->get('subscribe_for_news') == 1) {
    			$subscribeForNews = 1;
    		}
    		$companyEmployeesCount=0;
    		if($this->request->request->get('comp_emp_count') !== "") {
    			$companyEmployeesCount = $this->request->request->get('comp_emp_count');
    		}
    		$showAmountOfViews=0;
    		if($this->request->request->get('show_amount_of_views') == 1) {
    			$showAmountOfViews = $this->request->request->get('show_amount_of_views');
    		}
    		$showAmountUsersApplied=0;
    		if($this->request->request->get('show_amount_users_applied') == 1) {
    			$showAmountUsersApplied = $this->request->request->get('show_amount_users_applied');
    		}
    		
    		$companyBenefits = $this->request->request->get('comp_benefits');
    		
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
    		
    		$this->fc->redirect('Company', 'company');
    		
    	}
    	
    }
}

?>