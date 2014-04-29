<?php
namespace HR\Company;

use HR\Core\Model;
use HR\Core\FrontendUtils;

class CompanyModel extends Model
{    		    
	public function getCompanyProfileById($companyId) {
		$sql = "SELECT name, mail, phone, contact_person AS contactPerson, logo_key AS logoKey, created_at AS createdAt, changed_at AS changedAt,
					   additional_info AS additionalInfo, new_vacancies AS newVacancies, subscribe_for_news AS subscribeForNews, linkedin AS linkedIn, facebook, twitter,
					   employees_count AS employeesCount, show_views_count AS showViewsCount, show_applicants_count AS showApplicantsCount
				FROM hr_company
				WHERE company_id=%d";
		 
		$sql = $this->mysql->format($sql, array($companyId));
		$result = $this->mysql->query($sql);
		 
		$companyInfo = $this->mysql->getRow($result);
		 
		return $companyInfo;
	}
	
	
	public function getCompanyOfficesById($companyId) {
		$sql = "SELECT id, name
				FROM hr_company_office
				WHERE company_id=%d";
		 
		$sql = $this->mysql->format($sql, array($companyId));
		$result = $this->mysql->query($sql);
		 
		$offices = $this->mysql->getDataSet();
		
		return $offices;
	}
		
	
	public function updateCompanyInfo($currentCompanyId, $companyTitle, $companyAdditionalInfo, $companyPhone, $companyEmail, $companyLinkedIn, $companyFacebook, $companyTwitter, $pictureKey, $subscribeForNewVacancies, $subscribeForNews, $companyEmployeesCount, $showAmountOfViews, $showAmountUsersApplied) {
		$sql = "UPDATE hr_company SET name='%s', additional_info='%s', phone='%s', mail='%s', linkedin='%s', facebook='%s', 
				twitter='%s', new_vacancies='%s', subscribe_for_news='%s', amount_of_emploees='%s', 
				show_amount_of_views='%s', show_amount_users_applied='%s', 
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
    
}

?>