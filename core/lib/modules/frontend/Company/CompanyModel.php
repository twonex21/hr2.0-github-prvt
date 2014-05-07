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
	
	
	public function getCompanyVacancies($companyId, $count = 30) {
		$vacancies = array();
		 
		$sql = "SELECT v.vacancy_id AS vacancyId, v.company_id AS companyId, v.title, c.name AS companyName, DATE_FORMAT(v.deadline, '%%d %%M, %%Y') AS deadline
    			FROM hr_vacancy v
    			INNER JOIN hr_company c ON v.company_id=c.company_id
    			WHERE c.company_id=%d AND v.status='%s'
    			ORDER BY v.created_at DESC
    			LIMIT %d";
		 
		$sql = $this->mysql->format($sql, array($companyId, VACANCY_STATUS_ACTIVE, $count));
		$result = $this->mysql->query($sql);
		$vacancies = $this->mysql->getDataSet($result);
		 
		return $vacancies;
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
	
}

?>