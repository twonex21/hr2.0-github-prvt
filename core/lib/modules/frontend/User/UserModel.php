<?php
namespace HR\User;

use HR\Core\Model;
use HR\Core\FrontendUtils;

class UserModel extends Model
{    		    
	public function getUserProfileById($userId) {
    	$sql = "SELECT CONCAT(first_name, ' ', last_name) AS fullName, mail, location, linkedin AS linkedIn, 
    				   DATE_FORMAT(birth_date, '%%d %%M, %%Y') AS birthDate, bio, picture_key AS pictureKey, resume_key AS resumeKey
				FROM hr_user
				WHERE user_id=%d";
    	
    	$sql = $this->mysql->format($sql, array($userId));
    	$result = $this->mysql->query($sql);
    	
    	$profileInfo = $this->mysql->getRow($result);
    	
    	return $profileInfo;
    }    
    
    
    public function getUserEducation($userId) {    	
    	$userEducation = array();
    	$sql = "SELECT ue.univer_id AS univerId, ue.faculty_id AS facultyId, ue.degree
    			FROM hr_user_education ue    	
    			WHERE ue.user_id=%d";
    	
    	$sql = $this->mysql->format($sql, array($userId));
    	$result = $this->mysql->query($sql);
    	
    	$univerIds = array();
    	while($row = $this->mysql->getNextResult($result)) {
    		$univerIds[] = $row['univerId'];    		
    		$userEducation[] = $row;
    	}
    	    	
    	if(!empty($userEducation)) {
    		$faculties = $this->qb->getFaculties($univerIds);
    		foreach($userEducation as $idx => $edu) {
	    		foreach($faculties as $faculty) {
	    			if($faculty['univerId'] == $edu['univerId']) {
	    				$userEducation[$idx]['faculties'][] = $faculty;
	    			}
	    		}
    		}
    		
    		return $userEducation;
    	} 
    	
    	// Doing this way for array to have at least one element to show initial form block
    	return array('');
    }
    
    
    public function getUserExperience($userId) {
    	$userExperience = array();
    	$sql = "SELECT ue.industry_id AS industryId, ue.spec_id AS specId, ue.years
    			FROM hr_user_experience ue    			
    			WHERE ue.user_id=%d";
    	
    	$sql = $this->mysql->format($sql, array($userId));
    	$result = $this->mysql->query($sql);
    	
    	$industryIds = array();
    	while($row = $this->mysql->getNextResult($result)) {
    		$industryIds[] = $row['industryId'];
    		$userExperience[] = $row;		
    	}

    	if(!empty($userExperience)) {
    		$specs = $this->qb->getSpecializations($industryIds);
    		foreach($userExperience as $idx => $exp) {
	    		foreach($specs as $spec) {
	    			if($spec['industryId'] == $exp['industryId']) {
	    				$userExperience[$idx]['specs'][] = $spec;
	    			}
	    		}
    		}
    		
    		return $userExperience;
    	} 
    	
    	// Doing this way for array to have at least one element to show initial form block
    	return array('');
    }    
    
    
    public function getUserLanguages($userId) {
    	$userLanguages = array();
    	$sql = "SELECT language, level
    			FROM hr_user_language
    			WHERE user_id=%d";
    	
    	$sql = $this->mysql->format($sql, array($userId));
    	$result = $this->mysql->query($sql);
    	
    	$userLanguages = $this->mysql->getDataSet();

    	if(!empty($userLanguages)) {
    		return $userLanguages;
    	}
    	
    	// Doing this way for array to have at least one element to show initial form block
    	return array('');
    }
    
    
    public function getUserSkills($userId) {
    	$userSkills = array();
    	$sql = "SELECT sk.industry_id AS industryId, sk.spec_id AS specId, sk.name, usk.years
    			FROM hr_user_skill usk
    			INNER JOIN hr_skill sk ON sk.skill_id=usk.skill_id
    			WHERE usk.user_id=%d";
    	
    	$sql = $this->mysql->format($sql, array($userId));
    	$result = $this->mysql->query($sql);
    	    	
    	$experienceIds = array('industry' => array(), 'spec' => array());    	
    	while($row = $this->mysql->getNextResult($result)) {    		
    		if(!in_array($row['industryId'], $experienceIds['industry'])) {
    			$experienceIds['industry'][] = $row['industryId'];
    		}
    		if(!in_array($row['specId'], $experienceIds['spec'])) {
    			$experienceIds['spec'][] = $row['specId'];
    		}
    		    		
			$userSkills['userSkills'][] = $row;    		
    	}

    	if(!empty($userSkills)) {
    		$userSkills['allSkills'] = $this->qb->getSkills($experienceIds);

    		return $userSkills;
    	}
    	
    	// Doing this way for array to have at least one element to show initial form block
    	return array('userSkills' => '');
    }
    
    
    public function getUserSoftSkills($userId) {
    	$softSkills = array();
    	$sql = "SELECT uss.soft_id AS softId, uss.level
    			FROM hr_user_soft_skill uss    			
    			WHERE uss.user_id=%d";
    	
    	$sql = $this->mysql->format($sql, array($userId));
    	$result = $this->mysql->query($sql);
    	
    	$softSkills = $this->mysql->getDataSet();

    	if(!empty($softSkills)) {
    		return $softSkills;
    	}
    	
    	// Doing this way for array to have at least one element to show initial form block
    	return array('');
    }
    
	
	public function updateUserProfile($userId, $firstName, $lastName, $mail, $location, $linkedIn, $newPassword, $birthDate, $profileBio, $pictureKey, $resumeKey) {
    	$sql = "UPDATE hr_user SET first_name='%s', last_name='%s', mail='%s', location='%s', linkedin='%s', bio='%s', changed_at=NOW()";
    	$params = array($firstName, $lastName, $mail, $location, $linkedIn, $profileBio);

    	if($newPassword != '') {
    		// Changing password
    		$sql .= ", password='%s'";
    		$params[] = FrontendUtils::generateBcryptHash($newPassword);
    	}
    	
    	if($birthDate != '') {
    		$sql .= ", birth_date='%s'";
    		$params[] = date('Y-m-d', strtotime($birthDate));
    	}
    	
    	if($pictureKey != '') {
    		$sql .= ", picture_key='%s'";
    		$params[] = $pictureKey;
    	}
    	
    	if($resumeKey != '') {
    		$sql .= ", resume_key='%s'";
    		$params[] = $resumeKey;
    	}
    	
    	$sql .= " WHERE user_id=%d";
    	$params[] = $userId;
    	
    	// Since we're dealing with user input for the most security
    	// Executing prepared statements with parameter binding
    	$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);    	
    	$this->mysql->query($sql, SQL_PREPARED_QUERY);    	
    }
    
    
    public function updateUserEducation($userId, $education) {
    	$sql = "";
    	$params = array();
    	
    	// At first deleting all user education rows to be replaced with new ones
    	$sql = "DELETE FROM hr_user_education WHERE user_id=%d";
    	$sql = $this->mysql->format($sql, array($userId));
    	$this->mysql->query($sql);
    	
    	// Inserting new values
    	if(!empty($education)) {
    		$sql = "INSERT INTO hr_user_education (user_id, univer_id, faculty_id, degree, changed_at) VALUES";
    		foreach($education as $idx => $eduItem) {
    			$sql .= "(%d, %d, %d, '%s', NOW()),";
    			array_push($params, $userId, $eduItem['university']);
    			
    			if(isset($eduItem['faculty'])) {
    				array_push($params, $eduItem['faculty']);
    			} else {
    				array_push($params, 0);
    			}
    			
    			if(isset($eduItem['degree'])) {
    				array_push($params, $eduItem['degree']);
    			} else {
    				array_push($params, '');
    			}    			    			    			    		
    		}
    		
    		$sql = trim($sql, ",");
    		
    		// Executing prepared statements with parameter binding
	    	$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);
	    	$this->mysql->query($sql, SQL_PREPARED_QUERY);
    	}
    }
    
    
    public function updateUserExperience($userId, $experience) {
    	$sql = "";
    	$params = array();
    	
    	// At first deleting all user experience rows to be replaced with new ones
    	$sql = "DELETE FROM hr_user_experience WHERE user_id=%d";
    	$sql = $this->mysql->format($sql, array($userId));
    	$this->mysql->query($sql);
    	
    	// Inserting new values
    	if(!empty($experience)) {
    		$sql = "INSERT INTO hr_user_experience (user_id, industry_id, spec_id, years, changed_at) VALUES";
    		foreach($experience as $idx => $expItem) {
    			$sql .= "(%d, %d, %d, '%s', NOW()),";
    			array_push($params, $userId, $expItem['industry']);
    			
    			if(isset($expItem['spec'])) {
    				array_push($params, $expItem['spec']);
    			} else {
    				array_push($params, 0);
    			}
    			
    			if(isset($expItem['years'])) {
    				array_push($params, $expItem['years']);
    			} else {
    				array_push($params, 0);
    			}
    		}
    		
    		$sql = trim($sql, ",");

    		// Executing prepared statements with parameter binding
	    	$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);
	    	$this->mysql->query($sql, SQL_PREPARED_QUERY);
    	}
    }
    
    
    public function updateUserLanguages($userId, $languages) {
    	$sql = "";
    	$params = array();
    	
    	// At first deleting all user language rows to be replaced with new ones
    	$sql = "DELETE FROM hr_user_language WHERE user_id=%d";
    	$sql = $this->mysql->format($sql, array($userId));
    	$this->mysql->query($sql);
    	
    	// Inserting new values
    	if(!empty($languages)) {
    		$sql = "INSERT INTO hr_user_language (user_id, language, level, changed_at) VALUES";
    		foreach($languages as $lang => $level) {
    			$sql .= "(%d, '%s', '%s', NOW()),";
    			array_push($params, $userId, $lang, $level);    			    			
    		}
    		
    		$sql = trim($sql, ",");

    		// Executing prepared statements with parameter binding
	    	$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);
	    	$this->mysql->query($sql, SQL_PREPARED_QUERY);
    	}
    }
    
    
    public function updateUserSkills($userId, $skills) {
    	$sql = "";
    	$params = array();
    	
    	// At first deleting all user language rows to be replaced with new ones
    	$sql = "DELETE FROM hr_user_skill WHERE user_id=%d";
    	$sql = $this->mysql->format($sql, array($userId));
    	$this->mysql->query($sql);
    	
    	// Inserting new values
    	if(!empty($skills)) {
    		$sql = "INSERT INTO hr_user_skill (user_id, skill_id, years, changed_at) VALUES";
    		foreach($skills as $skill => $years) {
    			$sql .= "(%d, %d, '%s', NOW()),";
    			array_push($params, $userId, $skill, $years);    			
    		}
    		
    		$sql = trim($sql, ",");

    		// Executing prepared statements with parameter binding
	    	$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);
	    	$this->mysql->query($sql, SQL_PREPARED_QUERY);
    	}
    }            
    
    
    public function updateUserSoftSkills($userId, $softSkills) {
    	$sql = "";
    	$params = array();
    	
    	// At first deleting all user language rows to be replaced with new ones
    	$sql = "DELETE FROM hr_user_soft_skill WHERE user_id=%d";
    	$sql = $this->mysql->format($sql, array($userId));
    	$this->mysql->query($sql);
    	
    	// Inserting new values
    	if(!empty($softSkills)) {
    		$sql = "INSERT INTO hr_user_soft_skill (user_id, soft_id, level, changed_at) VALUES";
    		foreach($softSkills as $softSkillId => $level) {
    			$sql .= "(%d, %d, '%s', NOW()),";
    			array_push($params, $userId, $softSkillId, $level);
    		}
    		
    		$sql = trim($sql, ",");

    		// Executing prepared statements with parameter binding
	    	$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);
	    	$this->mysql->query($sql, SQL_PREPARED_QUERY);
    	}
    }
    
    
    public function storeUserSearchInfo($userId, $fullName, $location, $experienceStr, $skillStr) {
    	$sql = "REPLACE INTO hr_user_search (user_id, name, location, experience, skills)
    			VALUES (%d, '%s', '%s', '%s', '%s')";
    	$params = array($userId, $fullName, $location, $experienceStr, $skillStr);
    
    	// Since we're dealing with user input for the most security
    	// Executing prepared statements with parameter binding
    	$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);
    	$this->mysql->query($sql, SQL_PREPARED_QUERY);
    }
    
        
    public function addWantToWork($userId, $companyId) {
    	$sql = "INSERT INTO hr_company_workers (user_id, company_id, created_at)
    			VALUES (%d, %d, NOW())";
    	$params = array($userId, $companyId);
    
    	$sql = $this->mysql->format($sql, $params);
    	$this->mysql->query($sql);
    }
    
    
    public function getWantToWorkCompanies($userId) {
    	$companies = array();
    	$sql = "SELECT c.company_id AS companyId, c.name 
    			FROM hr_company_workers cw
    			INNER JOIN hr_company c ON c.company_id=cw.company_id
    			WHERE user_id=%d";
    	$params = array($userId);
    
    	$sql = $this->mysql->format($sql, $params);
    	$result = $this->mysql->query($sql);
    	
    	while($row = $this->mysql->getNextResult($result)) {
    		$row['idHash'] = FrontendUtils::hrEncode($row['companyId']);
    		$companies[] = $row;
    	}
    	
    	return $companies;
    }

    
    public function getUserApplications($userId, $user) {
    	$applications = array();
    
    	$sql = "SELECT v.vacancy_id AS vacancyId, v.title AS vacancyTitle, v.status AS vacancyStatus,
				       c.company_id AS companyId, c.name AS companyName, DATE_FORMAT(va.created_at, '%%Y-%%m-%%d') AS appliedAt
				FROM hr_vacancy_application va
				INNER JOIN hr_vacancy v ON v.vacancy_id=va.vacancy_id
				INNER JOIN hr_company c ON c.company_id=v.company_id
				WHERE va.user_id=%d
				ORDER BY va.created_at DESC";
    	$sql = $this->mysql->format($sql, array($userId));
    	$result = $this->mysql->query($sql);
    
    	$vacancies = array();
    	while($row = $this->mysql->getNextResult($result)) {    		
			$vacancy = array();
			// TODO: Think about getting all user or vacancy data in one call
			$vacancy['education'] = $this->qb->getVacancyEducation($row['vacancyId']);
			$vacancy['experience'] = $this->qb->getVacancyExperience($row['vacancyId']);
			$vacancy['languages'] = $this->qb->getVacancyLanguages($row['vacancyId']);
			$vacancy['skills'] = $this->qb->getVacancySkills($row['vacancyId']);
			$vacancy['softSkills'] = $this->qb->getVacancySoftSkills($row['vacancyId']);    
    			
    		if(!empty($vacancy) && !empty($user)) {
    			$row['matching'] = FrontendUtils::calculateMatching($user, $vacancy);
    		}
    			
    		$row['idHash'] = FrontendUtils::hrEncode($row['companyId']);
    			
    		$applications[] = $row;
    	}
    
    	return $applications;
    }
}

?>