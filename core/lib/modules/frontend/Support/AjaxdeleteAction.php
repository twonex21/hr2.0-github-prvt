<?php
namespace HR\Support;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class AjaxDeleteAction extends Action implements ActionInterface 
{
    function perform() {
    	$currentId = null; // Represents currentUserId in case of user and currentCompanyId in case of company
    	$key = '';
        $types = array('temp', 'user', 'company', 'resume', 'vacancy');
        $type = '';
        $filePath = '';        
        	
        $this->response->jsonPrepare();
        
        // Getting authorized role id
        if($this->session->isUserAuthorized()) {
    		$currentId = $this->session->getCurrentUserId();
        } elseif($this->session->isCompanyAuthorized()) {
        	$currentId = $this->session->getCurrentCompanyId();
        }

		if($this->request->request->has('key')) {
			$key = $this->request->request->get('key');
		}
		
		if($this->request->request->has('type') && in_array($this->request->request->get('type'), $types)) {
			$type = $this->request->request->get('type');			
			
			$resetMethod = 'reset' . ucfirst($type) . 'File';
			if(method_exists($this->qb, $resetMethod)) {
				// Deleting from db
				if($this->qb->$resetMethod($currentId, $key)) {					
					// Physically deleting file(s)
					$filePathMethod = 'get' . ucfirst($type) . 'FilePath';
					if(method_exists('HR\Core\FrontendUtils', $filePathMethod)) {
						$filePath = FrontendUtils::$filePathMethod($key);
						if(is_array($filePath)) {
							foreach($filePath as $path) {
								if($path != '') {
									FrontendUtils::deleteFile($path);
								}
							}
						} else if($filePath != '') {					
							FrontendUtils::deleteFile($filePath);
						}
					}
					
					$this->response->jsonSetStatus(SUCCESS);
				}
			}						
		}
		
        // Output
    	$this->response->jsonOutput();
    }
}

?>