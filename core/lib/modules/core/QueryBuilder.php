<?php
namespace HR\Core;

class QueryBuilder extends Model
{
       
    public function __construct($dbcon = null) {
    	parent::__construct();
    }
    
      
    public function getUserSessionDataById($userId) {
    	$user = array();
    	
    	$sql = "SELECT user_id AS ID, mail, first_name AS firstName, last_name AS lastName, CONCAT(first_name, ' ', last_name) AS fullName, 
    				   resume_key AS resumeKey, DATE_FORMAT(created_at, '%%Y-%%m-%%d') AS registrationDate
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
    

    public function getUserProfileById($userId) {
    	$sql = "SELECT user_id AS userId, CONCAT(first_name, ' ', last_name) AS fullName, mail, location, linkedin AS linkedIn,
    				   bio, picture_key AS pictureKey, resume_key AS resumeKey,
    				   YEAR(CURDATE()) - YEAR(birth_date) - (RIGHT(CURDATE(), 5) < RIGHT(birth_date, 5)) AS age
				FROM hr_user
				WHERE user_id=%d";
    	 
    	$sql = $this->mysql->format($sql, array($userId));
    	$result = $this->mysql->query($sql);
    	 
    	$profileInfo = $this->mysql->getRow($result);
    	 
    	return $profileInfo;
    }
    
    
    public function getUserEducation($userId) {
    	$userEducation = array();
    	$sql = "SELECT ue.univer_id AS univerId, u.name AS univerName, ue.faculty_id AS facultyId, f.name AS facultyName, ue.degree
    			FROM hr_user_education ue
    			INNER JOIN hr_university u ON u.univer_id=ue.univer_id
    			INNER JOIN hr_university_faculty f ON f.faculty_id=ue.faculty_id
    			WHERE ue.user_id=%d";
    	 
    	$sql = $this->mysql->format($sql, array($userId));
    	$result = $this->mysql->query($sql);
    	     	
    	$userEducation = $this->mysql->getDataSet($result);

		return $userEducation;    	
    }
    
    
    public function getUserExperience($userId) {
    	$userExperience = array();
    	$sql = "SELECT ue.industry_id AS industryId, i.name AS industryName, s.name AS specName, ue.spec_id AS specId, ue.years
    			FROM hr_user_experience ue
    			INNER JOIN hr_industry i ON i.industry_id=ue.industry_id
    			INNER JOIN hr_specialization s ON s.spec_id=ue.spec_id
    			WHERE ue.user_id=%d
    			ORDER BY ue.exp_id DESC";
    	 
    	$sql = $this->mysql->format($sql, array($userId));
    	$result = $this->mysql->query($sql);

    	$userExperience = $this->mysql->getDataSet($result);
    	
    	return $userExperience;    	
    }
    
    
    public function getUserLanguages($userId) {
    	$userLanguages = array();
    	$sql = "SELECT language, level
    			FROM hr_user_language
    			WHERE user_id=%d";
    	 
    	$sql = $this->mysql->format($sql, array($userId));
    	$result = $this->mysql->query($sql);
    	 
    	$userLanguages = $this->mysql->getDataSet();
    	    
    	return $userLanguages;    	
    }
    
    
    public function getUserSkills($userId) {
    	$userSkills = array();
    	$sql = "SELECT sk.name, usk.years
    			FROM hr_user_skill usk
    			INNER JOIN hr_skill sk ON sk.skill_id=usk.skill_id
    			WHERE usk.user_id=%d";
    	 
    	$sql = $this->mysql->format($sql, array($userId));
    	$result = $this->mysql->query($sql);
    
    	$userSkills = $this->mysql->getDataSet($result);

    	return $userSkills;
    }
    
    
    public function getUserSoftSkills($userId) {
    	$softSkills = array();
    	$sql = "SELECT uss.soft_id AS softId, ss.name, uss.level
    			FROM hr_user_soft_skill uss
    			INNER JOIN hr_soft_skill ss ON ss.soft_id=uss.soft_id
    			WHERE uss.user_id=%d";
    	 
    	$sql = $this->mysql->format($sql, array($userId));
    	$result = $this->mysql->query($sql);
    	 
    	$softSkills = $this->mysql->getDataSet();
        	
    	return $softSkills;    	
    }
        
    
    public function getCompanyBenefits($companyId) {
    	$sql = "SELECT cb.benefit_id AS benefitId, b.name as benefitName
				FROM hr_company_benefit cb
				INNER JOIN hr_benefit b ON b.benefit_id=cb.benefit_id
				WHERE cb.company_id=%d";
    		
    	$sql = $this->mysql->format($sql, array($companyId));
    	$result = $this->mysql->query($sql);
    		
    	$benefits = $this->mysql->getRow($result);
    
    	return $benefits;
    }
    
    public function getVacancyInfo($vacancyId) {
    	$sql = "SELECT v.vacancy_id AS vacancyId, v.title, v.company_id AS companyId, c.name AS companyName, c.mail AS companyMail, v.location, v.info AS additionalInfo, DATE_FORMAT(v.deadline, '%%d %%M, %%Y') AS deadline, v.file_key AS fileKey,
    				   v.views, v.show_applicants_count AS showApplicantsCount, v.show_viewers_count AS showViewersCount, v.show_wanttowork_count AS showWantToWorkCount,
    				   (SELECT COUNT(DISTINCT va.appl_id) FROM hr_vacancy_application va WHERE va.vacancy_id=v.vacancy_id) AS applicantsCount
				FROM hr_vacancy v
    			INNER JOIN hr_company c ON c.company_id=v.company_id
				WHERE v.vacancy_id=%d";
    	 
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$result = $this->mysql->query($sql);
    	 
    	$vacancyInfo = $this->mysql->getRow($result);
    	 
    	return $vacancyInfo;
    }
    
    
    public function getVacancyEducation($vacancyId) {
    	$vacancyEducation = array();
    	$sql = "SELECT ved.industry_id AS industryId, i.name AS industryName, ved.degree
    			FROM hr_vacancy_education ved
    			INNER JOIN hr_industry i ON i.industry_id=ved.industry_id
    			WHERE ved.vacancy_id=%d";
    	 
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$result = $this->mysql->query($sql);
    	 
    	$vacancyEducation = $this->mysql->getDataSet();
        	
    	return $vacancyEducation;    	
    }
    
    
    public function getVacancyExperience($vacancyId) {
    	$vacancyExperience = array();
    	$sql = "SELECT vex.industry_id AS industryId, i.name AS industryName, s.name AS specName, vex.spec_id AS specId, vex.years
    			FROM hr_vacancy_experience vex
    			INNER JOIN hr_industry i ON i.industry_id=vex.industry_id
    			INNER JOIN hr_specialization s ON s.spec_id=vex.spec_id
    			WHERE vex.vacancy_id=%d";
    	 
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$result = $this->mysql->query($sql);
    	     	
    	$vacancyExperience = $this->mysql->getDataSet($result);
    
    	return $vacancyExperience;    	 
    }
    
    
    public function getVacancyLanguages($vacancyId) {
    	$vacancyLanguages = array();
    	$sql = "SELECT language, level
    			FROM hr_vacancy_language
    			WHERE vacancy_id=%d";
    	 
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$result = $this->mysql->query($sql);
    	 
    	$vacancyLanguages = $this->mysql->getDataSet();
        	
    	return $vacancyLanguages;
    }
    
    
    public function getVacancySkills($vacancyId) {
    	$vacancySkills = array();
    	$sql = "SELECT sk.name, vsk.years
    			FROM hr_vacancy_skill vsk
    			INNER JOIN hr_skill sk ON sk.skill_id=vsk.skill_id
    			WHERE vsk.vacancy_id=%d";
    	 
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$result = $this->mysql->query($sql);

    	$vacancySkills = $this->mysql->getDataSet($result);
    	
    	return $vacancySkills;
    }
    
    
    public function getVacancySoftSkills($vacancyId) {
    	$softSkills = array();
    	$sql = "SELECT vss.soft_id AS softId, ss.name, vss.level
    			FROM hr_vacancy_soft_skill vss
    			INNER JOIN hr_soft_skill ss ON ss.soft_id=vss.soft_id
    			WHERE vss.vacancy_id=%d";
    	 
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$result = $this->mysql->query($sql);
    	 
    	$softSkills = $this->mysql->getDataSet();
    
    	return $softSkills;
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
    
    	return $vacancyBenefits;
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
    
    
    public function getCompanyById($companyId) {
    	$company = array();
    	 
    	$sql = "SELECT company_id AS companyId, mail, name, phone, contact_person AS contactPerson, DATE_FORMAT(created_at, '%%Y-%%m-%%d') AS registrationDate
    			FROM hr_company
    			WHERE company_id=%d";
    	 
    	$sql = $this->mysql->format($sql, array($companyId));
    	$result = $this->mysql->query($sql);
    	 
    	// Getting the result row and checking if it corresponds to company session attribues
    	$company = $this->mysql->getRow($result);
    
    	return $company;
    }
        
    
    public function vacancyExists($vacancyId) {    	
    	$sql = "SELECT COUNT(*) AS count FROM hr_vacancy WHERE vacancy_id=%d";
    	
    	$sql = $this->mysql->format($sql, array($vacancyId));
    	$result = $this->mysql->query($sql);
    	
    	return ($this->mysql->getField('count', $result) > 0);
    }
    
    
    public function hasApplied($vacancyId, $userId) {
    	$sql = "SELECT COUNT(*) AS count FROM hr_vacancy_application WHERE vacancy_id=%d AND user_id=%d";
    	
    	$sql = $this->mysql->format($sql, array($vacancyId, $userId));
    	$result = $this->mysql->query($sql);
    	
    	return ($this->mysql->getField('count', $result) > 0);
    }
    
    
    public function applyToVacancy($vacancyId, $userId) {
    	$sql = "INSERT INTO hr_vacancy_application (vacancy_id, user_id, created_at)
    			VALUES (%d, %d, NOW())";
    	
    	$sql = $this->mysql->format($sql, array($vacancyId, $userId), SQL_PREPARED_QUERY);
    	$this->mysql->query($sql, SQL_PREPARED_QUERY);
    }
}

?>