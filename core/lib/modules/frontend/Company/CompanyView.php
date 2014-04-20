<?php
namespace HR\Company;

use HR\Core\View;

class CompanyView extends View
{	
	function showCreateProfilePage() {

		
        $this->render('create.tpl', 'CONTENT');
        $this->finish();
    }

}

?>