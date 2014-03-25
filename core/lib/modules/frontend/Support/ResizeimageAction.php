<?php
namespace HR\Supprot;

use HR\Core\FrontendUtils;
class ResizeImageAction extends Action implements ActionInterface {

    function perform($reqParameters) {
        $s = 0;
        $tmp = 0;
        $key = "";
        $paramType = 1;
        $paramValue = 80;
        $type = 1; // 1 for USER, 2 for COMPANY                
        
        if(isset($reqParameters['s']) && is_numeric($reqParameters['s'])) {
            $s = $reqParameters['s'];
        }
        
        if(isset($reqParameters['tmp']) && is_numeric($reqParameters['tmp'])) {
            $tmp = $reqParameters['tmp'];
        }
        
        if(isset($reqParameters['key'])) {
            $key = $reqParameters['key'];            
        }
        
        if(isset($reqParameters['tp'])) {
            $type = $reqParameters['tp'];            
        }
                
          
        switch ($s) {      
   			case 1: $paramValue = LARGE_IMAGE_WIDTH; break;
   			case 2: $paramValue = MIDDLE_IMAGE_WIDTH; break;
   			case 3: $paramValue = SMALL_IMAGE_WIDTH; break;
   			case 4: $paramValue = EXTRA_LARGE_IMAGE_WIDTH; break;
        }
           
            
        if($tmp == 1) {
        	$filepath = FrontendUtils::getTempImagePath($key);
        } else {
        	if($type == 1) {
        		$filepath = FrontendUtils::getUserImagePath($key);		
        	} elseif($type == 2) {
        		$filepath = FrontendUtils::getCompanyImagePath($key);
        	}
        }
        
            
    	if($filepath!='' && $filepath!=null)
        	FrontendUtils::outputImage($filepath,$paramType,$paramValue);
    }
}
//EOF
?>