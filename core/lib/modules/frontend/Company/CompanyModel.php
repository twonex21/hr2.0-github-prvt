<?php
namespace HR\Company;

use HR\Core\Model;
use HR\Core\FrontendUtils;

class CompanyModel extends Model
{    		    
	public function getCompanyProfileById($userId) {
		$sql = "SELECT name,mail,phone,contact_person,created_at,changed_at 
				FROM hr_company
				WHERE company_id=%d";
		 
		$sql = $this->mysql->format($sql, array($userId));
		$result = $this->mysql->query($sql);
		 
		$companyInfo = $this->mysql->getRow($result);
		 
		return $companyInfo;
	}
    
}

?>