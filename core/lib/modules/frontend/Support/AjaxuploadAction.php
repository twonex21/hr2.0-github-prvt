<?php
namespace HR\Support;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class AjaxUploadAction extends Action implements ActionInterface 
{
    function perform() {
    	$file = array();
        $type = '';
        $errorMessage = '';
        $tempFile = '';
        	
        $this->response->jsonPrepare();

		if($this->request->files->has('upload-file')) {
			$file = $this->request->files->get('upload-file');
			$type = TYPE_FILE;
		} elseif($this->request->files->has('upload-picture')) {
			$file = $this->request->files->get('upload-picture');
			$type = TYPE_PICTURE;
		}

    	if(isset($file['name']) && $file['name'] != '') {        						
    		if(!FrontendUtils::isUploadFileValid($file, $type, $errorMessage)) {
    			// Attempt to upload invalid file
    			// Passing error message
    			$this->response->jsonSetErrorMessage($errorMessage);
    		} else {
    			// Uploading valid file
    			$keepName = ($type == TYPE_FILE);
            	$tempFile = FrontendUtils::uploadFile($file['tmp_name'], $file['name'], $keepName);

            	if($tempFile == '') {
            		// Some error occured during upload
    				// Passing error message
            		$this->response->jsonSetErrorMessage(constant('VALIDATION_' . $type . '_UPLOAD_ERROR'));
            	} else {
            		$this->response->jsonSet('tempFile', $tempFile);
            		$this->response->jsonSetStatus(SUCCESS);
            	}
    		}
        }
        // Output
    	$this->response->jsonOutput();
    }
}
//EOF
?>