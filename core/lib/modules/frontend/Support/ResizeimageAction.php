<?php
namespace HR\Support;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class ResizeImageAction extends Action implements ActionInterface 
{
    function perform() {
        $sizeType = 0;
        $isTemp = false;
        $filePath = '';
        $fileName = '';
        $dimensions = array('w', 'h');
        $dimension = 'w'; 					// Either 'width' or 'height'
        $dimensionSize = MIDDLE_IMAGE_SIZE; // Default size
        $type = 1;	 						// 1 for USER, 2 for COMPANY
        $square = false;
        
        if($this->request->query->has('s') && $this->request->query->isDigit('s')) {
            $sizeType = $this->request->query->getInt('s');
        }
        
        if($this->request->query->has('dim') && in_array($this->request->query->get('dim'), $dimensions)) {
            $dimension = $this->request->query->get('dim');
        }
        
        if(!$this->request->query->isNullOrEmpty('key')) {
            $fileName = $this->request->query->get('key');
        }
        
        $square = !$this->request->query->isNullOrEmpty('sq');
        
        if($this->request->query->has('tmp') && $this->request->query->getInt('tmp') == 1) {
        	// Temporary image
        	$filePath = FrontendUtils::getTempFilePath($fileName);
        } elseif($this->request->query->has('tp') && $this->request->query->isDigit('tp')) {
        	$type = $this->request->query->getInt('tp');
            if($type == 1) {
            	// User picture
            	$filePath = FrontendUtils::getPicturePath($fileName, USER, $square);
            } elseif($type == 2) {
            	// Company picture
            	$filePath = FrontendUtils::getPicturePath($fileName, COMPANY, $square);
            }
        }                                
            
        switch ($sizeType) {      
   			case 1: $dimensionSize = LARGE_IMAGE_SIZE; break;
   			case 2: $dimensionSize = MIDDLE_IMAGE_SIZE; break;
   			case 3: $dimensionSize = SMALL_IMAGE_SIZE;
        }        
            
    	if($filePath != '')
        	FrontendUtils::outputImage($filePath, $dimension, $dimensionSize);
    }
}
//EOF
?>