<?php
namespace HR\Support;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class AjaxDeleteAction extends Action implements ActionInterface 
{
    function perform() {    	
    	$key = '';
        $types = array('temp', 'user', 'company', 'resume', 'vacancy');
        $type = '';
        $filePath = '';        
        	
        $this->response->jsonPrepare();
                
		if($this->request->request->has('key')) {
			$key = $this->request->request->get('key');
		}
		
		if($this->request->request->has('type') && in_array($this->request->request->get('type'), $types)) {
			$type = $this->request->request->get('type');									
			
			if($type == 'temp') {
				$this->deleteFile($type, $key);
			} else {
				$resetMethod = 'reset' . ucfirst($type) . 'File';
				if(method_exists($this->qb, $resetMethod)) {
					// Deleting from db
					if($this->qb->$resetMethod($key)) {
						// Physically deleting file(s)
						$this->deleteFile($type, $key);
					}
				}
			}												
		}
		
        // Output
    	$this->response->jsonOutput();
    }
    
    
    private function deleteFile($type, $key) {
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

?>