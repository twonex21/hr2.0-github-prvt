<?php
namespace HR\Company;

use HR\Core\View;

class CompanyView extends View
{	
	function showCreateCompanyProfilePage($companyProfile, $companyOffices, $allBenefits, $companyBenefits) {

		//basic info
		$this->assign('_companyProfile', $companyProfile);
		$this->assign('_companyOffices', $companyOffices);
		
		//benefits
		$this->assign('_allBenefits', $allBenefits);
		$this->assign('_companyBenefits', $companyBenefits);
		
		
        $this->render('create.tpl', 'CONTENT');
        $this->finish();
    }
    
	function showCompanyProfilePage($companyProfile, $companyOffices, $allBenefits, $companyBenefits) {

		//basic info
		$this->assign('_companyProfile', $companyProfile);
		$this->assign('_companyOffices', $companyOffices);
		
		//benefits
		$this->assign('_allBenefits', $allBenefits);
		$this->assign('_companyBenefits', $companyBenefits);
		
        $this->render('profile.tpl', 'CONTENT');
        $this->finish();
    }

}

?>