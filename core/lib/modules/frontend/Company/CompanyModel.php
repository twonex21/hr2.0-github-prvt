<?php
namespace HR\Company;

use HR\Core\Model;
use HR\Core\FrontendUtils;

class CompanyModel extends Model
{    		    
	public function getCompanyProfileById($companyId) {
		$sql = "SELECT company_id AS companyId, name, mail, phone, contact_person AS contactPerson, logo_key AS logoKey, created_at AS createdAt, changed_at AS changedAt,
					   additional_info AS additionalInfo, new_vacancies AS newVacancies, subscribe_for_news AS subscribeForNews, linkedin AS linkedIn, facebook, twitter,
					   employees_count AS employeesCount, show_views_count AS showViewsCount, show_applicants_count AS showApplicantsCount, page_views AS pageViews
				FROM hr_company
				WHERE company_id=%d";
		 
		$sql = $this->mysql->format($sql, array($companyId));
		$result = $this->mysql->query($sql);
		 
		$companyInfo = $this->mysql->getRow($result);
		 
		return $companyInfo;
	}
	
	
	public function getCompanyOfficesById($companyId) {
		$sql = "SELECT office_id, address
				FROM hr_company_office
				WHERE company_id=%d";
		 
		$sql = $this->mysql->format($sql, array($companyId)); 
		$result = $this->mysql->query($sql);
		$offices = $this->mysql->getDataSet();
		
		return $offices;
	}
	
	
	public function updateCompanyInfo($currentCompanyId, $companyTitle, $companyAdditionalInfo, $companyPhone, $companyEmail, $companyLinkedIn, $companyFacebook, $companyTwitter, $pictureKey, $subscribeForNewVacancies, $subscribeForNews, $companyEmployeesCount, $showAmountOfViews, $showAmountUsersApplied) {
		$sql = "UPDATE hr_company SET name='%s', additional_info='%s', phone='%s', mail='%s', linkedin='%s', facebook='%s', 
				twitter='%s', new_vacancies='%s', subscribe_for_news='%s', employees_count='%s', 
				show_views_count='%s', 	show_applicants_count='%s', 
				changed_at=NOW()";
		
		//setting default values
		$params = array($companyTitle, $companyAdditionalInfo, $companyPhone, $companyEmail, $companyLinkedIn, $companyFacebook, 
				        $companyTwitter, $subscribeForNewVacancies, $subscribeForNews, $companyEmployeesCount, $showAmountOfViews, 
				        $showAmountUsersApplied);
	
		if($pictureKey != '') {
			$sql .= ", logo_key='%s'";
			$params[] = $pictureKey;
		}
		
		$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);
		$this->mysql->query($sql, SQL_PREPARED_QUERY);

	}
	
	public function updateCompanyOffices($currentCompanyId, $companyOffices){
		
		$sql = "";
		$params = array();
		 
		// delete all old company offices
		$sql = "DELETE FROM hr_company_office WHERE company_id=%d";
		$sql = $this->mysql->format($sql, array($currentCompanyId));
		$this->mysql->query($sql);
		
		// add new company offices
		if(!empty($companyOffices)) {
			
			$sql = "INSERT INTO hr_company_office (company_id, address, changed_at) VALUES";
			
			foreach($companyOffices as $companyOfficeName) {
				$sql .= "(%d, '%s', NOW()),";
				array_push($params, $currentCompanyId);
				array_push($params, $companyOfficeName);
			}
		
			$sql = trim($sql, ",");
		
			// Executing prepared statements with parameter binding
			$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);
			$this->mysql->query($sql, SQL_PREPARED_QUERY);
		}
	}
	
	public function updateCompanyBenefits($currentCompanyId, $companyBenefits){
		
		$sql = "";
		$params = array();
		 
		// delete all old company benefits
		$sql = "DELETE FROM hr_company_benefit WHERE company_id=%d";
		$sql = $this->mysql->format($sql, array($currentCompanyId));
		$this->mysql->query($sql);
		
		// add new company benefits
		if(!empty($companyBenefits)) {
			
			$sql = "INSERT INTO hr_company_benefit (company_id, benefit_id, changed_at) VALUES";
			
			foreach($companyBenefits as $companyBenefit) {
				$sql .= "(%d, %d, NOW()),";
				array_push($params, $currentCompanyId);
				array_push($params, $companyBenefit);
			}
		
			$sql = trim($sql, ",");
		
			// Executing prepared statements with parameter binding
			$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);
			$this->mysql->query($sql, SQL_PREPARED_QUERY);
		}
	}
    
	public function incrementPageViews($companyId){
		$sql = "UPDATE hr_company SET page_views = page_views + 1 WHERE company_id=%d";
		$sql = $this->mysql->format($sql, array($companyId));
		$this->mysql->query($sql);
	}
	
	public function logCompanyPageView($companyId, $roleId, $type){
		
		//checking if log record is present
		$sql = "SELECT *
				FROM hr_company_page_view
				WHERE company_id=%d AND role_id=%d AND type='%s'";
		$sql = $this->mysql->format($sql, array($companyId, $roleId, $type));
		$result = $this->mysql->query($sql);
		$row = $this->mysql->getRow($result);
		
		if(empty($row)){
			$sql = "INSERT INTO hr_company_page_view (company_id, role_id, type, changed_at) VALUES (%d, %d, '%s', NOW())";
			$sql = $this->mysql->format($sql, array($companyId, $roleId, $type));
			$this->mysql->query($sql);
		}
	}
	
		
	public function getMaxCompanyPageViews(){
		$sql = "SELECT MAX(page_views) AS maxPageViews FROM hr_company";
		$result = $this->mysql->query($sql);
		$row = $this->mysql->getRow($result);
		
		
		//TODO move this 1000 to some config ?
		if(!isset($row['maxPageViews']) || $row['maxPageViews'] < 100){
			return 100;
		}

		return $row['maxPageViews'];
	}
	
	
	public function getUsersAppliedCount($companyId){
		$sql = "SELECT count(*) as count
				FROM hr_vacancy_application AS va
				INNER JOIN hr_vacancy AS v ON va.vacancy_id = v.vacancy_id
				WHERE v.company_id = %d";
		$sql = $this->mysql->format($sql, array($companyId));
		$result = $this->mysql->query($sql);
		$row = $this->mysql->getRow($result);
		
		return $row['count'];
	}
	
	public function addSubscriptionForOpenings($companyId, $userId){
		
		if( !$this->isSubscriptionForOpenings($companyId, $userId) ){
			$sql = "INSERT INTO hr_company_subscription (company_id, user_id, changed_at) VALUES (%d, %d, NOW())";
			$sql = $this->mysql->format($sql, array($companyId, $userId));
			$this->mysql->query($sql);
			
			return true;
		}else{
			//already subscribed
			return false;
		}
		
	}
	
	public function isSubscriptionForOpenings($companyId, $userId){
		$sql = "SELECT *
				FROM hr_company_subscription
				WHERE company_id=%d AND user_id=%d";
		$sql = $this->mysql->format($sql, array($companyId, $userId));
		$result = $this->mysql->query($sql);
		$row = $this->mysql->getRow($result);
		
		if(empty($row)){
			return false;
		}else{
			return true;
		}
		
	}
	

	public function getCompanyApplicants($companyId) {
		$applicants = array();
		
		$sql = "SELECT v.vacancy_id AS vacancyId, v.title AS vacancyTitle, v.status AS vacancyStatus, u.user_id AS userId, 
					   CONCAT(u.first_name, ' ', u.last_name) AS fullName, DATE_FORMAT(va.created_at, '%%Y-%%m-%%d') AS appliedAt
				FROM hr_vacancy_application va
				INNER JOIN hr_vacancy v ON v.vacancy_id=va.vacancy_id	
				INNER JOIN hr_user u ON u.user_id=va.user_id	
				WHERE v.company_id=%d
				ORDER BY va.created_at DESC";		
		$sql = $this->mysql->format($sql, array($companyId));
		$result = $this->mysql->query($sql);
		
		$users = array();
		$vacancies = array();
		while($row = $this->mysql->getNextResult($result)) {
			if(isset($vacancies[$row['vacancyId']])) {
				$vacancy = $vacancies[$row['vacancyId']];
			} else {
				$vacancy = array();
				// TODO: Think about getting all user or vacancy data in one call
				$vacancy['education'] = $this->qb->getVacancyEducation($row['vacancyId']);
				$vacancy['experience'] = $this->qb->getVacancyExperience($row['vacancyId']);
				$vacancy['languages'] = $this->qb->getVacancyLanguages($row['vacancyId']);
				$vacancy['skills'] = $this->qb->getVacancySkills($row['vacancyId']);
				$vacancy['softSkills'] = $this->qb->getVacancySoftSkills($row['vacancyId']);
				
				$vacancies[$row['vacancyId']] = $vacancy;
			}
			
			if(isset($users[$row['userId']])) {
				$user = $users[$row['userId']];
			} else {
				$user = array();
				// TODO: Think about getting all user or vacancy data in one call
				$user['education'] = $this->qb->getUserEducation($row['userId']);
				$user['experience'] = $this->qb->getUserExperience($row['userId']);
				$user['languages'] = $this->qb->getUserLanguages($row['userId']);
				$user['skills'] = $this->qb->getUserSkills($row['userId']);
				$user['softSkills'] = $this->qb->getUserSoftSkills($row['userId']);
				
				$users[$row['userId']] = $user;
			}
			
			if(!empty($vacancy) && !empty($user)) {								 
				$row['matching'] = FrontendUtils::calculateMatching($user, $vacancy);
			}
			
			$row['idHash'] = FrontendUtils::hrEncode($row['userId']);
			
			$applicants[] = $row;
		}
		
		return $applicants;
	}
	
	
	public function getCompanyWorkers($companyId, $vacancies) {
		$applicants = array();
		
		$sql = "SELECT u.user_id AS userId, CONCAT(u.first_name, ' ', u.last_name) AS fullName, DATE_FORMAT(cw.created_at, '%%Y-%%m-%%d') AS appliedAt
				FROM hr_company_workers cw
				INNER JOIN hr_user u ON cw.user_id=u.user_id 
				WHERE cw.company_id=%d
				ORDER BY cw.created_at DESC";		
		$sql = $this->mysql->format($sql, array($companyId));
		$result = $this->mysql->query($sql);
		
		while($row = $this->mysql->getNextResult($result)) {						
			if(!empty($vacancies)) {
				$user = array();
				// TODO: Think about getting all user or vacancy data in one call
				$user['education'] = $this->qb->getUserEducation($row['userId']);
				$user['experience'] = $this->qb->getUserExperience($row['userId']);
				$user['languages'] = $this->qb->getUserLanguages($row['userId']);
				$user['skills'] = $this->qb->getUserSkills($row['userId']);
				$user['softSkills'] = $this->qb->getUserSoftSkills($row['userId']);
				 
				$maxMatching = 0;
				foreach($vacancies as $vacancy) {
					$row['matching'] = FrontendUtils::calculateMatching($user, $vacancy);
					if($row['matching'] > $maxMatching) {
						$maxMatching = $row['matching'];
						$row['matching']['vacancyId'] = $vacancy['vacancyId'];
						$row['matching']['vacancyTitle'] = $vacancy['title'];
					}
				}
			}
			
			$row['idHash'] = FrontendUtils::hrEncode($row['userId']);
			$workers[] = $row;			
		}
		
		return $workers;
	}
	
	
	public function addHiredUser($userId, $companyId) {
		$sql = "INSERT INTO hr_company_hiring (user_id, company_id, created_at)
    			VALUES (%d, %d, NOW())";
		$params = array($userId, $companyId);
		
		$sql = $this->mysql->format($sql, $params);
		$this->mysql->query($sql);
	}
	
}

?>