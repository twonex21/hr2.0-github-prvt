<?php
namespace HR\Vacancy;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class OpenAction extends Action implements ActionInterface 
{
    public function perform() {
    	$currentCompanyId = null;
    	$vacancyId = null;
    	$allIndustries = array();
    	$allUniverDegrees = array();    	
    	$allLanguages = array();
    	$allLanguageLevels = array();
    	$allSoftSkills = array();
    	$allSoftSkillLevels = array();
    	$companyBenefits = array();
    	
    	$vacancy = array();
    	
    	$isNew = false;
    	$title = '';
    	$location = '';
    	$statuses = array('ACTIVE', 'INACTIVE');
    	$status = '';
    	$deadline = '';
    	$additionalInfo = '';
    	$tmpFileKey = '';
    	$fileKey = '';
    	$showApplicantsCount = false;
    	$showViewersCount = false;
    	$showWantToWorkCount = false;
    	$tmpEduIndustries = $tmpEduDegrees = array();
    	$education = array();
    	$tmpExpIndustries = $tmpExpSpecs = $tmpExpYears = array();
    	$experience = array();
    	$tmpLangs = $tmpLangLevels = array();
    	$languages = array();
    	$tmpSkills = $tmpSkillYears = array();
    	$skills = array();
    	$tmpSoftSkills = array();
    	$softSkills = array();
    	$tmpSoftSkillLevels = array();
    	$softSkillLevels = array();
    	$benefits = array();
    	
    	// Setting page title
    	$this->setPageTitle(PT_OPEN_VACANCY);
    	
    	// Getting authorized user
    	$currentCompanyId = $this->session->getCurrentCompanyId();
    	
    	$data['industries'] = $this->qb->getIndustries();
    	$data['univerDegrees'] = unserialize(UNIVER_DEGREES);    	
    	$data['languages'] = unserialize(LANGUAGES);
    	$data['languageLevels'] = unserialize(LANGUAGE_LEVELS);
    	$data['softSkills'] = $this->qb->getSoftSkills();
    	$data['softSkillLevels'] = unserialize(SOFT_SKILL_LEVELS);
    	
    	$data['benefits'] = $this->qb->getCompanyBenefits($currentCompanyId);
    	if(empty($companyBenefits)) {
    		$data['benefits'] = $this->qb->getBenefits();
    	}    	    	    	

    	$vacancyId = $this->request->query->get('vid');    	
    	
    	if($this->request->request->isEmpty()) {
    		// POST is empty, no input parameters yet
    		// TODO: Think about foreach loop if all functions keep receiving only id
    		$vacancy['info'] = $this->model->getVacancyInfo($vacancyId);
    		$vacancy['education'] = $this->model->getVacancyEducation($vacancyId);
    		$vacancy['experience'] = $this->model->getVacancyExperience($vacancyId);
    		$vacancy['languages'] = $this->model->getVacancyLanguages($vacancyId);
    		$vacancy['skills'] = $this->model->getVacancySkills($vacancyId);
    		$vacancy['softSkills'] = $this->model->getVacancySoftSkills($vacancyId);
    		$vacancy['benefits'] = $this->model->getVacancyBenefits($vacancyId);
    		
    		$this->view->showOpenVacancyPage($data, $vacancy);
    	} else {
    		// Handling form input
    		// Basic vacancy info
    		if(!$this->request->request->isNullOrEmpty('title')) {    			
    			$title = $this->request->request->get('title');    			
    		} else {
    			return;
    		}
    		    		
    		if(!$this->request->request->isNullOrEmpty('location') && FrontendUtils::isLatin($this->request->request->get('location'))) {
    			$location = $this->request->request->get('location');
    		} else {
    			return;
    		}
    		    		
    		if(!$this->request->request->isNullOrEmpty('status') && in_array(strtoupper($this->request->request->get('status')), $statuses)) {
    			$status = strtoupper($this->request->request->get('status'));
    		} else {
    			return;
    		}
    		
    		if(!$this->request->request->isNullOrEmpty('deadline') && FrontendUtils::isDate($this->request->request->get('deadline'))) {
    			$deadline = $this->request->request->get('deadline');
    		} else {
    			return;
    		}
    		    		
    		$additionalInfo = $this->request->request->get('additional-info', '');    		
    		
    		if(!$this->request->request->isNullOrEmpty('temp-file')) {
    			$tmpFileKey = $this->request->request->get('temp-file');
    		
    			// Moving uploaded vacancy file to permanent location
    			if(FrontendUtils::saveTemporaryFile($tmpFileKey, VACANCY) !== false) {
    				$fileKey = $tmpFileKey;
    			}
    		}
    		
    		if($this->request->request->has('show-applicant-count')) {
    			$showApplicantsCount = true;
    		}
    		
    		if($this->request->request->has('show-viewer-count')) {
    			$showViewersCount = true;
    		}
    		
    		if($this->request->request->has('show-wanttowork-count')) {
    			$showWantToWorkCount = true;
    		}
    		
    		// Edication
    		if(!$this->request->request->isNullOrEmpty('edu-industries') && $this->request->request->isArray('edu-industries')) {
    			$tmpEduIndustries = $this->request->request->get('edu-industries');
    		    			
    			if(!$this->request->request->isNullOrEmpty('edu-degrees') && $this->request->request->isArray('edu-degrees')) {
    				$tmpEduDegrees = $this->request->request->get('edu-degrees');
    			}
    		
    			foreach($tmpEduIndustries as $idx => $industryId) {
    				if($industryId != 0 && isset($tmpEduDegrees[$idx])) {
    					$education[$industryId] = $tmpEduDegrees[$idx];
    				}
    			}    		
    		}
    		
    		// Work Experience
    		if(!$this->request->request->isNullOrEmpty('exp-industries') && $this->request->request->isArray('exp-industries')) {
    			$tmpExpIndustries = $this->request->request->get('exp-industries');
    		
    			if(!$this->request->request->isNullOrEmpty('exp-industry-specs') && $this->request->request->isArray('exp-industry-specs')) {
    				$tmpExpSpecs = $this->request->request->get('exp-industry-specs');
    			}
    		
    			if(!$this->request->request->isNullOrEmpty('exp-years') && $this->request->request->isArray('exp-years')) {
    				$tmpExpYears = $this->request->request->get('exp-years');
    			}
    		
    			foreach($tmpExpIndustries as $idx => $industryId) {
    				if($industryId != 0) {
    					$experience[$idx]['industry'] = $industryId;
    					if(isset($tmpExpSpecs[$idx])) {
    						$experience[$idx]['spec'] = $tmpExpSpecs[$idx];
    					}
    					if(isset($tmpExpYears[$idx])) {
    						$experience[$idx]['years'] = $tmpExpYears[$idx];
    					}
    				}
    			}
    		
    			// Removing duplicates
    			$experience = FrontendUtils::arrayUnique($experience);
    		}
    		
    		// Languages
    		if(!$this->request->request->isNullOrEmpty('langs') && $this->request->request->isArray('langs')) {
    			$tmpLangs = $this->request->request->get('langs');
    			 
    			if(!$this->request->request->isNullOrEmpty('lang-levels') && $this->request->request->isArray('lang-levels')) {
    				$tmpLangLevels = $this->request->request->get('lang-levels');
    			}
    		
    			foreach($tmpLangs as $idx => $lang) {
    				if($lang != '' && isset($tmpLangLevels[$idx])) {
    					$languages[$lang] = $tmpLangLevels[$idx];
    				}
    			}
    		}
    		
    		// Skills
    		if(!$this->request->request->isNullOrEmpty('skills') && $this->request->request->isArray('skills')) {
    			$tmpSkills = $this->request->request->get('skills');
    		
    			if(!$this->request->request->isNullOrEmpty('skill-years') && $this->request->request->isArray('skill-years')) {
    				$tmpSkillYears = $this->request->request->get('skill-years');
    			}
    		
    			foreach($tmpSkills as $idx => $skill) {
    				if($skill != '' && isset($tmpSkillYears[$idx])) {
    					$skills[$skill] = $tmpSkillYears[$idx];
    				}
    			}
    		}
    		
    		// Soft Skills
    		if(!$this->request->request->isNullOrEmpty('soft-skills') && $this->request->request->isArray('soft-skills')) {
    			$tmpSoftSkills = $this->request->request->get('soft-skills');
    			 
    			if(!$this->request->request->isNullOrEmpty('soft-levels') && $this->request->request->isArray('soft-levels')) {
    				$tmpSoftSkillLevels = $this->request->request->get('soft-levels');
    			}
    		
    			foreach($tmpSoftSkills as $idx => $softSkillId) {
    				if($softSkillId != 0 && isset($tmpSoftSkillLevels[$idx])) {
    					$softSkills[$softSkillId] = $tmpSoftSkillLevels[$idx];
    				}
    			}
    		}
    		
    		// Benefits
    		if(!$this->request->request->isNullOrEmpty('benefits') && $this->request->request->isArray('benefits')) {
    			$benefits = $this->request->request->get('benefits');
    		}
    		

    		// Handled form data, now saving it in db
    		if($vacancyId == null) {
    			// Creating new vacancy
    			$vacancyId = $this->model->createVacancy($currentCompanyId, 
    													 $title, 
    													 $location, 
    					 								 $additionalInfo, 
    													 $showApplicantsCount, 
    													 $showViewersCount, 
    													 $showWantToWorkCount, 
    													 $fileKey, 
    													 $deadline, 
    													 $status);
    			$isNew = true;
    		} 
    		
    		if($this->qb->vacancyExists($vacancyId)) {
	    		if(!$isNew) {
	    			// Updating old vacancy
	    			$this->model->updateVacancy($vacancyId, $title, $location, $additionalInfo, $showApplicantsCount, $showViewersCount, $showWantToWorkCount, $fileKey, $deadline, $status);
	    		}
				
	    		if(!empty($education)) {
	    			$this->model->updateVacancyEducation($vacancyId, $education);
	    		}
	    		if(!empty($experience)) {
	    			$this->model->updateVacancyExperience($vacancyId, $experience);
	    		}
	    		if(!empty($languages)) {
	    			$this->model->updateVacancyLanguages($vacancyId, $languages);
	    		}
	    		if(!empty($skills)) {
	    			$this->model->updateVacancySkills($vacancyId, $skills);
	    		}
	    		if(!empty($softSkills)) {
	    			$this->model->updateVacancySoftSkills($vacancyId, $softSkills);
	    		}
	    		if(!empty($benefits)) {
	    			$this->model->updateVacancyBenefits($vacancyId, $benefits);
	    		}
	    		
	    		// TODO: Think about adding new form fiield for industry
	    		$industryId = $this->qb->getVacancyIndustryId($vacancyId);
	    		$this->model->updateVacancyIndustryId($vacancyId, $industryId);
	    		
	    		$this->setMessage(MSG_TYPE_SUCCESS);
	    		
	    		$this->fc->redirect('vacancy', 'open', 'vid/' . $vacancyId . '/t/');
    		} else {
    			// Vacancy was not found
    			$this->fc->delegateNotFound();
    		}    		
    	}
    }
}

?>