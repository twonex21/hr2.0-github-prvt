<?php
namespace HR\Company;

use HR\Core\View;

class CompanyView extends View
{	
	function showCreateCompanyProfilePage($companyProfile) {

		$this->assign('_companyProfile', $companyProfile);
		
        $this->render('create.tpl', 'CONTENT');
        $this->finish();
    }

}

?>