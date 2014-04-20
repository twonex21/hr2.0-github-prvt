<?php
namespace HR\Company;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class CreateAction extends Action implements ActionInterface 
{
    public function perform() {

    	//echo "Company create page."; 
    	//Company create page.exit;
    	
    	$this->view->showCreateProfilePage();
    }
}

?>