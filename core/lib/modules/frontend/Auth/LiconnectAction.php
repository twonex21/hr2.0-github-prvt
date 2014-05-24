<?php
namespace HR\Auth;

use HappyR\LinkedIn\LinkedIn;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class LiconnectAction extends Action implements ActionInterface 
{
    public function perform() {
    	
    	$externalId = "";
    	$firstName = "";
    	$lastName = "";
    	$pictureUrl = "";
    	$mail = "";
    	$birthDate = "";
    	$location = "";
    	$linkedInProfileUrl = "";
    	
    	
    	$linkedIn = new LinkedIn(LI_APP_ID, LI_APP_SECRET);
		if ($linkedIn->isAuthenticated()) {
			$userInfo = $linkedIn->api('v1/people/~:(id,firstName,lastName,pictureUrl,emailAddress,dateOfBirth,location,publicProfileUrl)');
		
			$externalId = $userInfo['id'];
			
			if($this->model->externalUserExists($externalId, TYPE_LINKEDIN)) {
				// Existing user, just logging in
				$currentUser = $this->qb->getUserSessionDataByExternalId($externalId);
			} else {
				
				$firstName = $userInfo['firstName'];
				$lastName = $userInfo['lastName'];
				$linkedInProfileUrl = $userInfo['publicProfileUrl'];
				$mail = $userInfo['emailAddress'];
				
				//TODO check image quality
				if(isset($userInfo['pictureUrl'])){
					
					$pictureUrl = $userInfo['pictureUrl'];
					$picture = FrontendUtils::uploadExternalPicture($pictureUrl);
					
					if(!empty($picture)) {
						// Saving cropped version as well
						$pictureKey = $picture['key'];
						$extension = $picture['extension'];
						$picturePath = sprintf(USER_PICTURE_PATH, $pictureKey, $extension);
							
						$croppedPath = sprintf(USER_SQUARE_PICTURE_PATH, $pictureKey, $extension);
						FrontendUtils::cropAndSaveImage($picturePath, $croppedPath, PICTURE_CROP_DIMENSION, PICTURE_CROP_DIMENSION, $extension);
					}
				}
				if(isset($userInfo['dateOfBirth'])){
					$birthDate = $userInfo['dateOfBirth']['year'] . "-" . $userInfo['dateOfBirth']['month'] . "-" . $userInfo['dateOfBirth']['day'];
				}
				if(isset($userInfo['location'])){
					$location = $userInfo['location']['name'];
				}
			
				// Registering external user
				if($this->qb->notAlreadyUsed($mail)) {
					$currentUserId = $this->model->registerExternalUser(TYPE_LINKEDIN, $externalId, $mail, $firstName, $lastName, $birthDate, $location, $pictureKey, $linkedInProfileUrl);
					$currentUser = $this->qb->getUserSessionDataById($currentUserId);
				}
			}
		}
		
		if(!is_null($currentUser)) {
			// Storing user into session
			$this->session->setCurrentUser($currentUser);
			if(!$this->qb->isUserProfileComplete($currentUser['ID'])) {
				// User Profile is not complete, redirecting to create page
				$redirectUrl = '/user/create/';
			}
		
			$this->view->showLinkedInPage(true, $redirectUrl);
		}
    }
}

?>