<?php
namespace HR\Vacancy;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class DeleteAction extends Action implements ActionInterface 
{
    public function perform() {
    	$currentCompanyId = 0;    	
    	$vacancyId = 0;    	
    	
    	$currentCompanyId = $this->session->getCurrentCompanyId();
    	
    	$this->response->jsonPrepare();    	
    	$this->response->jsonSet(JSON_MESSAGE, 'Some error occured deleting vacancy');
    	
    	if(!$this->request->request->isNullOrEmpty('vid')) {
    		$vacancyId = $this->request->request->getInt('vid');    		
    		
    		if($this->qb->isVacancyOwner($currentCompanyId, $vacancyId)) {
    			$this->model->deleteVacancy($vacancyId);
    			$this->response->jsonSet(JSON_MESSAGE, 'Vacancy successfully deleted');
    			$this->response->jsonSetStatus(SUCCESS);
    		}
    	}
    	
    	// Output
    	$this->response->jsonOutput();
    }
}

?>