<?php
namespace HR\User;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class CreateAction extends Action implements ActionInterface 
{
    public function perform() {
    	$currentUserId = null;
    	$data = array();    	
    	$user = array();
    	$updatedUser = array();
    	
    	$firstName = '';
    	$lastName = '';
    	$mail = '';
    	$location = '';
    	$linkedIn = '';
    	$newPassword = '';
    	$birthDate = '';
    	$profileBio = '';
    	$tmpResumeKey = '';
    	$resumeKey = '';
    	$pictureKey = '';
    	$tmpPictureKey = '';
    	$tmpUnivers = $tmpUniverFaculties = $tmpUniverDegrees = array();
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
    	
    	// Setting page title
    	$this->setPageTitle(PT_EDIT_USER_PROFILE);

    	// Getting authorized user
    	$currentUserId = $this->session->getCurrentUserId();
    	
    	$data['universities'] = $this->qb->getUniversities();
    	$data['univerDegrees'] = unserialize(UNIVER_DEGREES);
    	$data['industries'] = $this->qb->getIndustries();
    	$data['languages'] = unserialize(LANGUAGES);
    	$data['languageLevels'] = unserialize(LANGUAGE_LEVELS);
    	$data['softSkills'] = $this->qb->getSoftSkills();
    	$data['softSkillLevels'] = unserialize(SOFT_SKILL_LEVELS);
    	
    	if($this->request->request->isEmpty()) {
    		// POST is empty, no input parameters yet
    		$user['profile'] = $this->model->getUserProfileById($currentUserId);
    		$user['education'] = $this->model->getUserEducation($currentUserId);
    		$user['experience'] = $this->model->getUserExperience($currentUserId);
    		$user['languages'] = $this->model->getUserLanguages($currentUserId);
    		$user['skills'] = $this->model->getUserSkills($currentUserId);
    		$user['softSkills'] = $this->model->getUserSoftSkills($currentUserId);
    		
    		$this->view->showCreateProfilePage($data, $user);
    	} else {
    		// Handling form input
    		
    		// Basic profile info
    		if(!$this->request->request->isNullOrEmpty('full-name') && FrontendUtils::isLatin($this->request->request->get('full-name'))) {
    			if(strpos($this->request->request->get('full-name'), ' ')) {
    				list($firstName, $lastName) = explode(' ', $this->request->request->get('full-name'));
    			} else {
    				$firstName = $this->request->request->get('full-name');
    			}
    		}
    		
    		if(!$this->request->request->isNullOrEmpty('email') && FrontendUtils::isEmailAddress($this->request->request->get('email'))) {
    			$mail = $this->request->request->get('email');
    		}
    		
    		if(!$this->request->request->isNullOrEmpty('location') && FrontendUtils::isLatin($this->request->request->get('location'))) {
    			$location = $this->request->request->get('location');
    		}
    		
    		if(!$this->request->request->isNullOrEmpty('linkedin') && FrontendUtils::isLinkedIn($this->request->request->get('linkedin'))) {
    			$linkedIn = $this->request->request->get('linkedin');
    		}
    		
    		if(!$this->request->request->isNullOrEmpty('password') && FrontendUtils::isPasswordLength($this->request->request->get('password'))) {
    			$newPassword = $this->request->request->get('password');
    		}
    		
    		if(!$this->request->request->isNullOrEmpty('birth-date') && FrontendUtils::isDate($this->request->request->get('birth-date'))) {
    			$birthDate = $this->request->request->get('birth-date');
    		}
    		    		    		
    		if(!$this->request->request->isNullOrEmpty('profile-bio')) {
    			$profileBio = $this->request->request->get('profile-bio');
    		}
    		
    		if(!$this->request->request->isNullOrEmpty('temp-resume')) {    			
    			$tmpResumeKey = $this->request->request->get('temp-resume');
    			    			
    			// Moving uploaded resume to permanent location
				if(FrontendUtils::saveTemporaryFile($tmpResumeKey, RESUME) !== false) {					
					$resumeKey = $tmpResumeKey;
				}
    		}
    		
    		if(!$this->request->request->isNullOrEmpty('temp-picture')) {
    			// Stripping random 4 character prefix
    			$tmpPictureKey = $this->request->request->get('temp-picture');    			
    			
    			// Moving uploaded resume to permanent location
				$picturePath = FrontendUtils::saveTemporaryFile($tmpPictureKey, USER);
				if($picturePath !== false) {
					$pictureKey = $tmpPictureKey;									
					
					// Saving cropped version as well
					$fileNameParts = explode('.', $picturePath);
    				$extension = strtolower(end($fileNameParts));
    				$croppedPath = sprintf(USER_SQUARE_PICTURE_PATH, $pictureKey, $extension);
    				FrontendUtils::cropAndSaveImage($picturePath, $croppedPath, PICTURE_CROP_DIMENSION, PICTURE_CROP_DIMENSION, $extension);
				}
    		}
    		
    		// Edication
    		if(!$this->request->request->isNullOrEmpty('universities') && $this->request->request->isArray('universities')) {
    			$tmpUnivers = $this->request->request->get('universities');

				if(!$this->request->request->isNullOrEmpty('university-faculties') && $this->request->request->isArray('university-faculties')) {
	    			$tmpUniverFaculties = $this->request->request->get('university-faculties');
				}
				
				if(!$this->request->request->isNullOrEmpty('university-degrees') && $this->request->request->isArray('university-degrees')) {
	    			$tmpUniverDegrees = $this->request->request->get('university-degrees');
				}
				
				foreach($tmpUnivers as $idx => $univerId) {
					if($univerId != 0) {
						$education[$idx]['university'] = $univerId;
						if(isset($tmpUniverFaculties[$idx])) {
							$education[$idx]['faculty'] = $tmpUniverFaculties[$idx];
						}
						if(isset($tmpUniverDegrees[$idx])) {
							$education[$idx]['degree'] = $tmpUniverDegrees[$idx];
						}												
					}
				}
				
				// Removing duplicates
				$education = FrontendUtils::arrayUnique($education);
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
    		    		
    		
    		// Handled form data, now saving it in db
    		$this->model->updateUserProfile($currentUserId, $firstName, $lastName, $mail, $location, $linkedIn, $newPassword, $birthDate, $profileBio, $pictureKey, $resumeKey);
    		if(!empty($education)) {
    			$this->model->updateUserEducation($currentUserId, $education);
    		}
    		if(!empty($experience)) {
    			$this->model->updateUserExperience($currentUserId, $experience);
    		}
    		if(!empty($languages)) {
    			$this->model->updateUserLanguages($currentUserId, $languages);
    		}
    		if(!empty($skills)) {
    			$this->model->updateUserSkills($currentUserId, $skills);    		
    		}
    		if(!empty($softSkills)) {
    			$this->model->updateUserSoftSkills($currentUserId, $softSkills);
    		}
    		
    		// Updating session data as well
    		$updatedUser = $this->qb->getUserSessionDataById($currentUserId);
    		$this->session->setCurrentUser($updatedUser);
    		
    		$this->setMessage(MSG_TYPE_SUCCESS);
    		
    		$this->fc->redirect('user', 'profile');
    	}
    }
}

?>