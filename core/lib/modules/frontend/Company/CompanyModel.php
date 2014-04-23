<?php
namespace HR\Company;

use HR\Core\Model;
use HR\Core\FrontendUtils;

class CompanyModel extends Model
{    		    
	public function getCompanyProfileById($comapnyId) {
		$sql = "SELECT name, mail, phone, contact_person AS contactPerson, logo_key AS logoKey, created_at AS createdAt, changed_at AS changedAt,
				additional_info AS additionalInfo, new_vacancies AS newVacancies, subscribe_for_news AS subscribeForNews, linkedin AS linkedIn, facebook, twitter,
				amount_of_emploees AS amountOfEmploees, show_amount_of_views AS showAmountOfViews, show_amount_users_applied AS showAmountUsersApplied
				FROM hr_company
				WHERE company_id=%d";
		 
		$sql = $this->mysql->format($sql, array($comapnyId));
		$result = $this->mysql->query($sql);
		 
		$companyInfo = $this->mysql->getRow($result);
		 
		return $companyInfo;
	}
	
	public function getCompanyOfficesByCompanyId($comapnyId) {
		$sql = "SELECT id, name
				FROM hr_company_offices
				WHERE company_id=%d";
		 
		$sql = $this->mysql->format($sql, array($comapnyId));
		$result = $this->mysql->query($sql);
		 
		$companyInfo = array();
		while($data = $this->mysql->getRow($result)){
			$companyInfo[] = $data;
		}
		 
		return $companyInfo;
	}
    
}

?>