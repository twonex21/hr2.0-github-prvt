<?php

require_once LIB_DIR.'modules/frontend/Api/ApiModel.php';

class GetLocationAction extends FrontendController {

    function perform($reqParameters)
    {    	    	                                
    	$coords = "";
        $jsonArray = array("status" => "fail");
                
        if(!empty($_POST)) {
	    	if(isset($_POST["lat"]) && $_POST["lat"] != "" && isset($_POST["lng"]) && $_POST["lng"] != "") {
	    		$_SESSION["location"] = array("lat" => $_POST["lat"], "lng" => $_POST["lng"], "name" => "");
	    		
	    		$coords = $_POST["lat"].",".$_POST["lng"];
	    		$requestUrl = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$coords."&sensor=false";
	    		//$requestUrl = "http://maps.googleapis.com/maps/api/geocode/json?latlng=42.358431,-71.059773&sensor=false";
	    		
	    		// Initializing curl
				$curl = curl_init();
				 
				// Configuring curl options
				$curlOptions = array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => $requestUrl,
					CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json'),
					CURLOPT_URL => $requestUrl,
					CURLOPT_SSL_VERIFYPEER => 0,
					CURLOPT_SSL_VERIFYHOST => 2
				);
				 
				// Setting curl options
				curl_setopt_array($curl, $curlOptions);

				// Getting results
				$jsonOutput =  json_decode(curl_exec($curl));
				
				// Check for curl errors
				if ($error = curl_error($curl)) {
					$jsonOutput	= json_encode(array("status" => "fail", "error" => $error, "message" => "Error occured while retrieving results"));
				} else {
					// Parsing json response
					if($jsonOutput->status == "OK") {								            	
	            		for($i=0; $i < count($jsonOutput->results); $i++) {
	            			$currResult = $jsonOutput->results[$i];
	            			if(in_array("locality", $currResult->types)) {
	            				//$(".name .address").html(currResult.formatted_address);
	            				$currLocation = $currResult->formatted_address;
	            				$jsonArray["status"] = "ok";
	            				$jsonArray["location"] = $currLocation;
	            				$_SESSION["location"]["name"] = $currLocation;
	            				break;
	            				// TODO: Add location to header when design is ready
	            			}
	            		}
		            }
				}
				
				// Close handle
				curl_close($curl);
	    	}        	        	
        }

        echo json_encode($jsonArray);
    }
}
//EOF
?>