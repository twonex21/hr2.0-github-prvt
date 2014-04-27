<?php
namespace HR\Company;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class CompanyAction extends Action implements ActionInterface 
{
    public function perform() {
    	
    	$this->view->showCompanyPage();
    }        
}

?>