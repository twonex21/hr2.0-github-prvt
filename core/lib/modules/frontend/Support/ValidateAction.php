<?php
namespace HR\Support;

use HR\Core\FrontendUtils;

class ValidateAction extends FrontendController {

    function perform($reqParameters)
    {     
    	$jsonArray = array("status" => "ok");
    	$validationMessages = unserialize(VALIDATION_MESSAGES);
    	
    	if(!empty($_POST)) {
    		if(isset($_POST["fields"])) {
    			foreach($_POST["fields"] as $field) {
    				foreach($field["criterias"] as $criteria) {
    					if(method_exists('FrontendUtils', $criteria)) {
    						if(!FrontendUtils::$criteria($field["value"])) {
    							$jsonArray["status"] = "fail";
    							$jsonArray["messages"][$field["id"]] = $validationMessages[$criteria];
    							break;
    						}
    					}
    				}
    			}
    		}
    	}
    	
    	echo json_encode($jsonArray);
    }
}
//EOF
?>