<?php
namespace HR\Vacancy;

use HR\Core\Model;
use HR\Core\FrontendUtils;

class VacancyModel extends Model
{   	
	public function getVacancyInfo($vacancyId) {
    	$sql = "SELECT v.vacancy_id AS vacancyId, v.title, v.location, v.info AS additionalInfo, DATE_FORMAT(v.deadline, '%%d %%M, %%Y') AS deadline, v.status, v.file_key AS fileKey,
    				   v.show_applicants_count AS showApplicantsCount, v.show_viewers_count AS showViewersCount, v.show_wanttowork_count AS showWantToWorkCount    				    
				FROM hr_vacancy v
				WHERE v.vacancy_id=%d";
    	
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$result = $this->mysql->query($sql);
    	
    	$vacancyInfo = $this->mysql->getRow($result);
    	
    	return $vacancyInfo;
    }    
    
    
    public function getVacancyEducation($vacancyId) {    	
    	$vacancyEducation = array();
    	$sql = "SELECT ved.industry_id AS industryId, ved.degree
    			FROM hr_vacancy_education ved
    			WHERE ved.vacancy_id=%d";
    	    	
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$result = $this->mysql->query($sql);
    	
    	$vacancyEducation = $this->mysql->getDataSet();

    	if(!empty($vacancyEducation)) {
    		return $vacancyEducation;
    	}
    	
    	// Doing this way for array to have at least one element to show initial form block
    	return array('');
    }
    
    
    public function getVacancyExperience($vacancyId) {
    	$vacancyExperience = array();
    	$sql = "SELECT vex.industry_id AS industryId, vex.spec_id AS specId, vex.years
    			FROM hr_vacancy_experience vex    			
    			WHERE vex.vacancy_id=%d";
    	
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$result = $this->mysql->query($sql);
    	
    	$industryIds = array();
    	while($row = $this->mysql->getNextResult($result)) {
    		$industryIds[] = $row['industryId'];
    		$vacancyExperience[] = $row;    		
    	}

    	if(!empty($vacancyExperience)) {
    		$specs = $this->qb->getSpecializations($industryIds);
    		foreach($vacancyExperience as $idx => $exp) {
	    		foreach($specs as $spec) {
	    			if($spec['industryId'] == $exp['industryId']) {
	    				$vacancyExperience[$idx]['specs'][] = $spec;
	    			}
	    		}
    		}
    		
    		return $vacancyExperience;
    	} 
    	
    	// Doing this way for array to have at least one element to show initial form block
    	return array('');
    }    
    
    
    public function getVacancyLanguages($vacancyId) {
    	$vacancyLanguages = array();
    	$sql = "SELECT language, level
    			FROM hr_vacancy_language
    			WHERE vacancy_id=%d";
    	
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$result = $this->mysql->query($sql);
    	
    	$vacancyLanguages = $this->mysql->getDataSet();

    	if(!empty($vacancyLanguages)) {
    		return $vacancyLanguages;
    	}
    	
    	// Doing this way for array to have at least one element to show initial form block
    	return array('');
    }
    
    
    public function getVacancySkills($vacancyId) {
    	$vacancySkills = array();
    	$sql = "SELECT sk.industry_id AS industryId, sk.spec_id AS specId, sk.name, vsk.skill_id AS skillId, vsk.years
    			FROM hr_vacancy_skill vsk
    			INNER JOIN hr_skill sk ON sk.skill_id=vsk.skill_id
    			WHERE vsk.vacancy_id=%d";
    	
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$result = $this->mysql->query($sql);
    	    	
    	$experienceIds = array('industry' => array(), 'spec' => array());    	
    	while($row = $this->mysql->getNextResult($result)) {    		
    		if(!in_array($row['industryId'], $experienceIds['industry'])) {
    			$experienceIds['industry'][] = $row['industryId'];
    		}
    		if(!in_array($row['specId'], $experienceIds['spec'])) {
    			$experienceIds['spec'][] = $row['specId'];
    		}
    		    		
			$vacancySkills['vacancySkills'][] = $row;    		
    	}

    	if(!empty($vacancySkills)) {
    		$vacancySkills['allSkills'] = $this->qb->getSkills($experienceIds);

    		return $vacancySkills;
    	}
    	
    	// Doing this way for array to have at least one element to show initial form block
    	return array('vacancySkills' => '');
    }
    
    
    public function getVacancySoftSkills($vacancyId) {
    	$softSkills = array();
    	$sql = "SELECT vss.soft_id AS softId, vss.level
    			FROM hr_vacancy_soft_skill vss    			
    			WHERE vss.vacancy_id=%d";
    	
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$result = $this->mysql->query($sql);
    	
    	$softSkills = $this->mysql->getDataSet();

    	if(!empty($softSkills)) {
    		return $softSkills;
    	}
    	
    	// Doing this way for array to have at least one element to show initial form block
    	return array('');
    }
    
    
    public function getVacancyBenefits($vacancyId) {
    	$vacancyBenefits = array();
    	$sql = "SELECT vb.benefit_id AS benefitId, b.name
    			FROM hr_vacancy_benefit vb
    			INNER JOIN hr_benefit b ON b.benefit_id=vb.benefit_id
    			WHERE vb.vacancy_id=%d";
    	 
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$result = $this->mysql->query($sql);
    	 
    	$vacancyBenefits = $this->mysql->getDataSet();
    
    	if(!empty($vacancyBenefits)) {
    		return $vacancyBenefits;
    	}
    	 
    	// Doing this way for array to have at least one element to show initial form block
    	return array('');
    }
    
    
    public function createVacancy($companyId, $title, $location, $additionalInfo, $showApplicantsCount, $showViewersCount, $showWantToWorkCount, $fileKey, $deadline, $status) {
    	$vacancyId = null;
    	
    	$openedAt = ($status == VACANCY_STATUS_ACTIVE) ? 'NOW()' : 'NULL';
    	$sql = "INSERT INTO hr_vacancy (company_id, title, location, info, show_applicants_count, show_viewers_count, show_wanttowork_count, file_key, deadline, status, opened_at, created_at, changed_at)
    			VALUES (%d,	'%s', '%s', '%s', %d, %d, %d, '%s', '%s', '%s', " . $openedAt . ", NOW(), NOW())";
    	$params = array($companyId, $title, $location, $additionalInfo, $showApplicantsCount, $showViewersCount, $showWantToWorkCount, $fileKey, date('Y-m-d', strtotime($deadline)), $status);
    	
    	$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);
    	$this->mysql->query($sql, SQL_PREPARED_QUERY);
    	
    	$vacancyId = $this->mysql->getInsertID();
    	
    	return $vacancyId;
    }
    
    
    public function getVacancyStatus($vacancyId) {    	    	
    	$sql = "SELECT status FROM hr_vacancy WHERE vacancy_id=%d";
    	
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$result = $this->mysql->query($sql);
    	
    	return $this->mysql->getField('status', $result);
    }
	
    
	public function updateVacancy($vacancyId, $title, $location, $additionalInfo, $showApplicantsCount, $showViewersCount, $showWantToWorkCount, $fileKey, $deadline, $status, $oldStatus) {
    	$sql = "UPDATE hr_vacancy SET title='%s', location='%s', info='%s', show_applicants_count=%d, show_viewers_count=%d, show_wanttowork_count=%d, deadline='%s', status='%s', changed_at=NOW()";
    	$params = array($title, $location, $additionalInfo, $showApplicantsCount, $showViewersCount, $showWantToWorkCount, date('Y-m-d', strtotime($deadline)), $status);
    	
    	if($fileKey != '') {
    		$sql .= ", file_key='%s'";
    		$params[] = $fileKey;
    	}
    	
    	if($oldStatus == VACANCY_STATUS_INACTIVE && $status == VACANCY_STATUS_ACTIVE) {
    		$sql .= ", opened_at=NOW(), closed_at=NULL";
    	} elseif($oldStatus == VACANCY_STATUS_ACTIVE && $status == VACANCY_STATUS_INACTIVE) {
    		$sql .= ", closed_at=NOW()";
    	}
    	
    	$sql .= " WHERE vacancy_id=%d";
    	$params[] = $vacancyId;
    	
    	// Since we're dealing with user input for the most security
    	// Executing prepared statements with parameter binding
    	$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);    	
    	$this->mysql->query($sql, SQL_PREPARED_QUERY);    	
    }
    
    
	public function updateVacancyEducation($vacancyId, $education) {
    	$sql = "";
    	$params = array();
    	
    	// At first deleting all vacancy education rows to be replaced with new ones
    	$sql = "DELETE FROM hr_vacancy_education WHERE vacancy_id=%d";
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$this->mysql->query($sql);
    	
    	// Inserting new values
    	if(!empty($education)) {
    		$sql = "INSERT INTO hr_vacancy_education (vacancy_id, industry_id, degree, changed_at) VALUES";
    		foreach($education as $industryId => $degree) {
    			$sql .= "(%d, %d, '%s', NOW()),";
    			array_push($params, $vacancyId, $industryId, $degree);    			    			
    		}
    		
    		$sql = trim($sql, ",");

    		// Executing prepared statements with parameter binding
	    	$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);
	    	$this->mysql->query($sql, SQL_PREPARED_QUERY);
    	}
    }

    
    public function updateVacancyExperience($vacancyId, $experience) {
    	$sql = "";
    	$params = array();
    	
    	// At first deleting all vacancy experience rows to be replaced with new ones
    	$sql = "DELETE FROM hr_vacancy_experience WHERE vacancy_id=%d";
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$this->mysql->query($sql);
    	
    	// Inserting new values
    	if(!empty($experience)) {
    		$sql = "INSERT INTO hr_vacancy_experience (vacancy_id, industry_id, spec_id, years, changed_at) VALUES";
    		foreach($experience as $idx => $expItem) {
    			$sql .= "(%d, %d, %d, '%s', NOW()),";
    			array_push($params, $vacancyId, $expItem['industry']);
    			
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
    
    
    public function updateVacancyLanguages($vacancyId, $languages) {
    	$sql = "";
    	$params = array();
    	
    	// At first deleting all vacancy language rows to be replaced with new ones
    	$sql = "DELETE FROM hr_vacancy_language WHERE vacancy_id=%d";
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$this->mysql->query($sql);
    	
    	// Inserting new values
    	if(!empty($languages)) {
    		$sql = "INSERT INTO hr_vacancy_language (vacancy_id, language, level, changed_at) VALUES";
    		foreach($languages as $lang => $level) {
    			$sql .= "(%d, '%s', '%s', NOW()),";
    			array_push($params, $vacancyId, $lang, $level);    			    			
    		}
    		
    		$sql = trim($sql, ",");

    		// Executing prepared statements with parameter binding
	    	$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);
	    	$this->mysql->query($sql, SQL_PREPARED_QUERY);
    	}
    }
    
    
    public function updateVacancySkills($vacancyId, $skills) {
    	$sql = "";
    	$params = array();
    	
    	// At first deleting all user language rows to be replaced with new ones
    	$sql = "DELETE FROM hr_vacancy_skill WHERE vacancy_id=%d";
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$this->mysql->query($sql);
    	
    	// Inserting new values
    	if(!empty($skills)) {
    		$sql = "INSERT INTO hr_vacancy_skill (vacancy_id, skill_id, years, changed_at) VALUES";
    		foreach($skills as $skill => $years) {
    			$sql .= "(%d, %d, '%s', NOW()),";
    			array_push($params, $vacancyId, $skill, $years);    			
    		}
    		
    		$sql = trim($sql, ",");

    		// Executing prepared statements with parameter binding
	    	$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);
	    	$this->mysql->query($sql, SQL_PREPARED_QUERY);
    	}
    }            
    
    
    public function updateVacancySoftSkills($vacancyId, $softSkills) {
    	$sql = "";
    	$params = array();
    	
    	// At first deleting all user language rows to be replaced with new ones
    	$sql = "DELETE FROM hr_vacancy_soft_skill WHERE vacancy_id=%d";
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$this->mysql->query($sql);
    	
    	// Inserting new values
    	if(!empty($softSkills)) {
    		$sql = "INSERT INTO hr_vacancy_soft_skill (vacancy_id, soft_id, level, changed_at) VALUES";
    		foreach($softSkills as $softSkillId => $level) {
    			$sql .= "(%d, %d, '%s', NOW()),";
    			array_push($params, $vacancyId, $softSkillId, $level);
    		}
    		
    		$sql = trim($sql, ",");

    		// Executing prepared statements with parameter binding
	    	$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);
	    	$this->mysql->query($sql, SQL_PREPARED_QUERY);
    	}
    }
    
    
    public function updateVacancyBenefits($vacancyId, $benefits) {
    	$sql = "";
    	$params = array();
    	
    	// At first deleting all user language rows to be replaced with new ones
    	$sql = "DELETE FROM hr_vacancy_benefit WHERE vacancy_id=%d";
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$this->mysql->query($sql);
    	
    	// Inserting new values
    	if(!empty($benefits)) {
    		$sql = "INSERT INTO hr_vacancy_benefit (vacancy_id, benefit_id, changed_at) VALUES";
    		foreach($benefits as $benefitId) {
    			$sql .= "(%d, %d, NOW()),";
    			array_push($params, $vacancyId, $benefitId);
    		}
    		
    		$sql = trim($sql, ",");

    		// Executing prepared statements with parameter binding
	    	$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);
	    	$this->mysql->query($sql, SQL_PREPARED_QUERY);
    	}
    }
    
    
    public function updateVacancyIndustryId($vacancyId, $industryId) {
    	$sql = "UPDATE hr_vacancy SET industry_id=%d WHERE vacancy_id=%d";
    	$params = array($industryId, $vacancyId);
    	 
    	$sql = $this->mysql->format($sql, $params);
    	$this->mysql->query($sql);
    }
    
    
    
    public function storeVacancySearchInfo($vacancyId, $title, $companyName, $location, $additionalInfo, $skillStr) {
    	$sql = "REPLACE INTO hr_vacancy_search (vacancy_id, title, company_name, location, info, skills)
    			VALUES (%d, '%s', '%s', '%s', '%s', '%s')";
    	$params = array($vacancyId, $title, $companyName, $location, $additionalInfo, $skillStr);
    	 
    	// Since we're dealing with user input for the most security
    	// Executing prepared statements with parameter binding
    	$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);
    	$this->mysql->query($sql, SQL_PREPARED_QUERY);
    }
    
    
    public function addVacancyView($vacancyId) {
    	$sql = "UPDATE hr_vacancy SET views=views+1 WHERE vacancy_id=%d";
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$this->mysql->query($sql);
    }
    
    
    public function storeVacancyView($vacancyId, $userRole, $userId) {
    	$sql = "INSERT INTO hr_vacancy_view (vacancy_id, role, role_user_id, created_at)
    			VALUES (%d, '%s', %d, NOW())";
    	$sql = $this->mysql->format($sql, array($vacancyId, $userRole, $userId));
    	$this->mysql->query($sql);
    }
    
    
    public function deleteVacancy($vacancyId) {
    	$sql = "UPDATE hr_vacancy SET status='%s' WHERE vacancy_id=%d";
    	$sql = $this->mysql->format($sql, array(VACANCY_STATUS_DELETED, $vacancyId));
    	$this->mysql->query($sql);
    }
}

?>