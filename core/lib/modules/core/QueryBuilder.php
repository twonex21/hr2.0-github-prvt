<?php
namespace HR\Core;

class QueryBuilder extends Model
{
       
    public function __construct($dbcon = null) {
    	parent::__construct();
    }
    
      
    public function getUserSessionDataById($userId) {
    	$user = array();
    	
    	$sql = "SELECT user_id AS ID, mail, first_name AS firstName, last_name AS lastName, DATE_FORMAT(created_at, '%%Y-%%m-%%d') AS registrationDate
    			FROM hr_user
    			WHERE user_id=%d";
    	
    	$sql = $this->mysql->format($sql, array($userId));
    	$result = $this->mysql->query($sql);
    	    
    	// Getting the result row and checking if it corresponds to user session attribues	
    	$user = FrontendUtils::validateSessionData($this->mysql->getRow($result), unserialize(SESSION_USER_ATTRIBUTES));

    	return $user;   	
    }
    
    
    
    public function getCompanySessionDataById($companyId) {
    	$company = array();
    	
    	$sql = "SELECT company_id AS ID, mail, name, phone, contact_person AS contactPerson, DATE_FORMAT(created_at, '%%Y-%%m-%%d') AS registrationDate
    			FROM hr_company
    			WHERE company_id=%d";
    	
    	$sql = $this->mysql->format($sql, array($companyId));
    	$result = $this->mysql->query($sql);
    	
    	// Getting the result row and checking if it corresponds to company session attribues	
    	$company = FrontendUtils::validateSessionData($this->mysql->getRow($result), unserialize(SESSION_COMPANY_ATTRIBUTES));

    	return $company;   	
    }
    
    
    public function getUniversities() {    	
    	$sql = "SELECT univer_id AS univerId, name FROM hr_university";
    	$result = $this->mysql->query($sql);
    	
    	return $this->mysql->getDataSet($result);    
    }
    
    
    public function getFaculties($univerIds) {
    	$faculties = array();
    	
    	$inSql = trim(str_repeat('%d, ', count($univerIds)), ', ');
    	$sql = "SELECT univer_id AS univerId, faculty_id AS facultyId, name 
    			FROM hr_university_faculty
    			WHERE univer_id IN($inSql)";
    	
    	$sql = $this->mysql->format($sql, $univerIds, SQL_PREPARED_QUERY);
    	$result = $this->mysql->query($sql, SQL_PREPARED_QUERY);
    	
    	while($row = $this->mysql->getNextResult($result)) {
    		$faculties[$row['facultyId']] = $row;
    	}
    	
    	return $faculties;
    }
    
    
    public function getIndustries() {    	
    	$sql = "SELECT industry_id AS industryId, name FROM hr_industry";
    	$result = $this->mysql->query($sql);
    	
    	return $this->mysql->getDataSet($result);    
    }
    
    
    public function getSpecializations($industryIds) {
    	$specializations = array();
    	
    	$inSql = trim(str_repeat('%d, ', count($industryIds)), ', ');
    	$sql = "SELECT industry_id AS industryId, spec_id AS specId, name 
    			FROM hr_specialization
    			WHERE industry_id IN($inSql)";
    	
    	$sql = $this->mysql->format($sql, $industryIds, SQL_PREPARED_QUERY);
    	$result = $this->mysql->query($sql, SQL_PREPARED_QUERY);
    	
    	while($row = $this->mysql->getNextResult($result)) {
    		$specializations[$row['specId']] = $row;
    	}
    	
    	return $specializations;
    }
    
    
    public function getSoftSkills() {    	    	
    	$sql = "SELECT soft_id AS softId, name FROM hr_soft_skill";    	
    	$result = $this->mysql->query($sql);
    	
    	return $this->mysql->getDataSet($result);
    }        
    
    
    public function getSkills($experienceIds) {
    	$skills = array();
    	
    	if(!empty($experienceIds['industry']) && !empty($experienceIds['spec'])) {
    		$inSql_1 = trim(str_repeat('%d, ', count($experienceIds['industry'])), ', ');
    		$inSql_2 = trim(str_repeat('%d, ', count($experienceIds['spec'])), ', ');
    		$params = array_merge($experienceIds['industry'], $experienceIds['spec']);
    		
	    	$sql = "SELECT sk.skill_id AS skillId, sk.name, i.name AS parentName
					FROM hr_skill sk
					INNER JOIN hr_industry i ON sk.industry_id=i.industry_id
					WHERE (sk.industry_id IN($inSql_1) AND sk.spec_id=0) OR (sk.spec_id IN($inSql_2) AND sk.spec_id!=0)
					GROUP BY sk.name
					ORDER BY i.industry_id, sk.name";

	    	$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);
	    	$result = $this->mysql->query($sql, SQL_PREPARED_QUERY);
	    	
	    	$skills = $this->mysql->getDataSet($result);
    	}
    	
    	return $skills;
    }
    
    
    public function getBenefits() {    	    	
    	$sql = "SELECT benefit_id AS benefitId, name FROM hr_benefit";    	
    	$result = $this->mysql->query($sql);
    	
    	return $this->mysql->getDataSet($result);
    }        
    
            
    public function resetUserFile($key) {
    	$updated = false;
    	$sql = "UPDATE hr_user SET picture_key='' WHERE picture_key='%s'";
    	
    	$sql = $this->mysql->format($sql, array($key));
    	$this->mysql->query($sql);
    	
    	// Picture key has been reset if query had affected rows
    	$updated = ($this->mysql->getAffectedRows() > 0);
    	
    	return $updated;
    }
    
    
    public function resetResumeFile($key) {
    	$updated = false;
    	$sql = "UPDATE hr_user SET resume_key='' WHERE resume_key='%s'";
    	
    	$sql = $this->mysql->format($sql, array($key));
    	$this->mysql->query($sql);
    	
    	// Picture key has been reset if query had affected rows
    	$updated = ($this->mysql->getAffectedRows() > 0);
    	
    	return $updated;
    }
    
    
    public function resetVacancyFile($key) {
    	$updated = false;
    	$sql = "UPDATE hr_vacancy SET file_key='' WHERE file_key='%s'";
    	
    	$sql = $this->mysql->format($sql, array($key));
    	$this->mysql->query($sql);
    	
    	// Picture key has been reset if query had affected rows
    	$updated = ($this->mysql->getAffectedRows() > 0);
    	
    	return $updated;
    }
    
    
    public function vacancyExists($vacancyId) {    	
    	$sql = "SELECT COUNT(*) AS count FROM hr_vacancy WHERE vacancy_id=%d";
    	
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$result = $this->mysql->query($sql);
    	
    	return ($this->mysql->getField('count', $result) > 0);
    }
}

?>