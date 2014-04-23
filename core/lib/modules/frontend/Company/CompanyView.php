<?php
namespace HR\Company;

use HR\Core\View;

class CompanyView extends View
{	
	function showCreateCompanyProfilePage($companyProfile, $companyOffices) {

		$this->assign('_companyProfile', $companyProfile);
		$this->assign('_companyOffices', $companyOffices);
		
        $this->render('create.tpl', 'CONTENT');
        $this->finish();
    }

}

?>