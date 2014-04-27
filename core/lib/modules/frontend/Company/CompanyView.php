<?php
namespace HR\Company;

use HR\Core\View;

class CompanyView extends View
{	
	function showCreateCompanyProfilePage($companyProfile, $companyOffices, $allBenefits, $companyBenefits) {

		$this->assign('_companyProfile', $companyProfile);
		$this->assign('_companyOffices', $companyOffices);
		
		//benefits
		$this->assign('_allBenefits', $allBenefits);
		$this->assign('_companyBenefits', $companyBenefits);
		
		
        $this->render('create.tpl', 'CONTENT');
        $this->finish();
    }

}

?>