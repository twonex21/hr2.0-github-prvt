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
		 
		$ret_result = array();
		while($data = $this->mysql->getRow($result)){
			$ret_result[] = $data;
		}
		return $ret_result;
	}
	
	public function getCompanyBenefitsByCompanyId($comapnyId) {
		$sql = "SELECT hr_company_benefits.company_benefits_id AS companyBenefitsId, hr_benefits.benefit_id AS benefitId, hr_benefits.name
				FROM hr_company_benefits
				INNER JOIN hr_benefits ON hr_company_benefits.benefit_id = hr_benefits.benefit_id
				WHERE hr_company_benefits.company_id=%d";

		$sql = $this->mysql->format($sql, array($comapnyId));
		$result = $this->mysql->query($sql);
		 
		$ret_result = array();
		while($data = $this->mysql->getRow($result)){
			$ret_result[] = $data;
		}
		return $ret_result;
	}
	
	public function getAllBenefits() {
		$sql = "SELECT benefit_id AS benefitId,name FROM hr_benefits";

		$result = $this->mysql->query($sql);
		 
		$ret_result = array();
		while($data = $this->mysql->getRow($result)){
			$ret_result[] = $data;
		}
		return $ret_result;
	}
    
}

?>