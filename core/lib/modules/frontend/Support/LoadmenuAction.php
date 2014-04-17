<?php
namespace HR\Support;

use HR\Core\ActionInterface;
use HR\Core\Action;

class LoadMenuAction extends Action implements ActionInterface 
{
    function perform() {
    	$parentIds = array();
    	$typesArray = array('faculties', 'specializations', 'skills');
    	$type = '';
    	
    	// Action gives json output
        $this->response->jsonPrepare();

        if(!$this->request->request->isNullOrEmpty('parentIds') && !$this->request->request->isNullOrEmpty('type') && in_array($this->request->request->get('type'), $typesArray)) {
        	$parentIds = $this->request->request->get('parentIds');
        	$type = $this->request->request->get('type');
        	
        	$methodName = 'get' . ucfirst($type);
        	if(method_exists($this->qb, $methodName)) {
        		$this->response->jsonSet('options', $this->qb->$methodName($parentIds));
        		$this->response->jsonSetStatus(SUCCESS);
        	}
        }
        
        $this->response->jsonOutput();
    }
}
//EOF
?>