<?php
namespace HR\Support;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class ValidateAction extends Action implements ActionInterface 
{
    function perform() {
    	$this->response->jsonPrepare(SUCCESS);
    	$validationMessages = unserialize(VALIDATION_MESSAGES);
    	
    	if(!$this->request->request->isEmpty()) {
    		// Post is not empty
    		if($this->request->request->has('fields')) {
    			// Looping through fields to be validated
    			foreach($this->request->request->get('fields') as $field) {
    				if(isset($field['criterias'])) {
    					// Looping through field criterias to validate against
	    				foreach($field['criterias'] as $criteria) {
	    					// Validating criteria
	    					if(method_exists('HR\Core\FrontendUtils', $criteria)) {
	    						if(!FrontendUtils::$criteria($field['value'])) {
	    							// Validation failed
	    							$this->response->jsonSetStatus(FAIL);
	    							$this->response->jsonErrorMessagesPush($field['id'], $validationMessages[$criteria]);    							
	    							break;
	    						}
	    					} elseif(method_exists($this->qb, $criteria)) {
	    						if(!$this->qb->$criteria($field['value'])) {
	    							// Validation failed
	    							$this->response->jsonSetStatus(FAIL);
	    							$this->response->jsonErrorMessagesPush($field['id'], $validationMessages[$criteria]);
	    							break;
	    						}
	    					}
	    				}
    				}
    			}
    		}
    	}
    	
    	$this->response->jsonOutput();
    }
}
//EOF
?>