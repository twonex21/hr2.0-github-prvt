<?php

/* This action is normally executed via cron job from shell
 * To trigger exectution this way the following command must be used:
 * /usr/bin/php -q PATH_TO_INDEX/index.php shell nearbypushes key/SHELL_KEY/param1/value1/param2/value2.../t/
 */


require_once LIB_DIR.'modules/core/FrontendUtils.php';
require_once LIB_DIR.'modules/core/Logger.php';
require_once LIB_DIR.'modules/frontend/Shell/ShellModel.php';
require_once LIB_DIR.'modules/frontend/Api/ApiUtils.php';

class NearbyPushesAction extends FrontendController {

    function perform($reqParameters)
    {    	
    	
        $model = new ShellModel();
        $apiUtils = new ApiUtils();
        $logger = Logger::getInstance();
        
        $videos = array();
        $interval = "6 HOUR";
        $nearbyUsers = array();
        $radius = 3;
                        
		if(isset($reqParameters["key"]) && $reqParameters["key"] == SHELL_KEY) {
			$logger->append("Sending nearby pushes started", Logger::HEADER);
			
			// Getting new videos (recorded within $interval) for pushes
			$videos = $model->getNewVideos($interval);
			$logger->append(sprintf("Found %d new video(s)", count($videos)), Logger::SUB_HEADER);
			
			foreach($videos as $video) {
				$nearbyUsers = array();
				
				if($video["lat"] && $video["lng"]) {
					// Getting users located nearby (within 3 miles radius) in the last 1 hour
					$nearbyUsers = $model->getNearbyUsers($video["accountId"], $video["lat"], $video["lng"], $radius);
					$logger->append(sprintf("Found %d nearby user(s) for video:%d", count($nearbyUsers), $video["videoId"]), Logger::SUB_HEADER);
					
					foreach($nearbyUsers as $user) {					
						$pushDevices = array();
						if(!empty($user["device_tokens"])) {
							$deviceTokens = explode(",", $user["device_tokens"]);
							$deviceTypes = explode(",", $user["device_types"]);
							
							foreach($deviceTokens as $idx => $token) {
								$pushDevices[$deviceTypes[$idx]][] = $token;
							}
						}				
						
						if(!empty($pushDevices)) {
							// Sending push notification
							$params = array("type" => "place", "id" => $video["venueId"]);
							
							if($user["accountId"] == 1 || $user["accountId"] == 11 || $user["accountId"] == 996 || $user["accountId"] == 1057) {
								$apiUtils->sendPushNotification($pushDevices, sprintf(PUSH_NEARBY_VIDEO, $video["tagName"], $video["venueName"]), $params);
								
								// Storing info about push notification into db
								$model->storePushInfo($user["accountId"], "NEARBY_VIDEO");
								$logger->append(sprintf("Sent push notification to user:%s about video:%d", $user["accountName"], $video["videoId"]), Logger::SUB_HEADER);
							}														
						}			
					}
				}
				
				// Setting push sent for the current video to exclude it from future runs
				$model->setVideoPushSent($video["videoId"]);
			}
			
			$logger->append("Sending nearby pushes ended", Logger::FOOTER);
			$logger->write();
		}
    }
}
//EOF
?>