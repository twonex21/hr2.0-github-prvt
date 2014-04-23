<?php
namespace HR\Company;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class CreateAction extends Action implements ActionInterface 
{
    public function perform() {

    	
    	// Setting page title
    	$this->setPageTitle('Company Page Creaton');
    	
    	$currentCompanyId = $this->session->getCurrentCompanyId();

    	if($this->request->request->isEmpty()) {
    		
    		// POST is empty, no input parameters yet
    		
    		//collecting data if it exists and passing to template
    		$companyProfile = $this->model->getCompanyProfileById($currentCompanyId);
    		$companyOffices = $this->model->getCompanyOfficesByCompanyId($currentCompanyId);

    		$this->view->showCreateCompanyProfilePage($companyProfile, $companyOffices);
    	} else {
    		
    	}
    	
    }
}

?>