<?php
namespace HR\Auth;

use Facebook\FacebookSession as FacebookSession;
use Facebook\FacebookRedirectLoginHelper as FacebookRedirectLoginHelper;
use Facebook\FacebookRequest as FacebookRequest;
use Facebook\GraphUser as GraphUser;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class FbconnectAction extends Action implements ActionInterface 
{
    public function perform() {
    	$fbSession = null;
    	$fbUser = null;
    	$currentUser = null;
    	$currentUserId = 0;
    	$externalId = '';
    	$pictureUrl = '';
    	$mail = '';
    	$firstName = '';
    	$lastName = '';
    	$birthDate = '';
    	$location = '';
    	
    	FacebookSession::setDefaultApplication(FB_APP_ID, FB_APP_SECRET);
    	$fbHelper = new FacebookRedirectLoginHelper('http://local.hr.am/auth/fbconnect/');
    	try {
    		// Getting facebook session
    		$fbSession = $fbHelper->getSessionFromRedirect();
    		if(!$fbSession->validate()) {
    			$fbSession = null;
    		}
    	} catch(\Exception $ex) {
    		$this->view->showFBPage(false);
    	}
    	
    	if ($fbSession) {
    		// Facebook user is authorized
    		$request = new FacebookRequest($fbSession, 'GET', '/me');
			$response = $request->execute();
			$fbUser = $response->getGraphObject(GraphUser::className());
			
			// Getting user facebook ID
			$externalId = $fbUser->getId();			
			
			if($this->model->externalUserExists($externalId, TYPE_FACEBOOK)) {
				// Existing user, just logging in
				$currentUser = $this->qb->getUserSessionDataByExternalId($externalId);				
			} else {				
				// Uploading user picture
				$pictureUrl = 'http://graph.facebook.com/' . $externalId . '/picture?type=large';
				$picture = FrontendUtils::uploadExternalPicture($pictureUrl);
				if(!empty($picture)) {
					// Saving cropped version as well	
					$pictureKey = $picture['key'];
					$extension = $picture['extension'];
					$picturePath = sprintf(USER_PICTURE_PATH, $pictureKey, $extension);					
					
					$croppedPath = sprintf(USER_SQUARE_PICTURE_PATH, $pictureKey, $extension);
					FrontendUtils::cropAndSaveImage($picturePath, $croppedPath, PICTURE_CROP_DIMENSION, PICTURE_CROP_DIMENSION, $extension);
				}
				
				// No such external user, registering new account
				$mail = $fbUser->getProperty('email');				
				$firstName = $fbUser->getFirstName();
				$lastName = $fbUser->getLastName();
				
				if($fbUser->getBirthday() != '') {
					$birthDate = $fbUser->getBirthday()->format('Y-m-d');
				}
				
				if($fbUser->getLocation()) {
					$fbLocation = $fbUser->getLocation();
					$location = $fbLocation->getProperty('name');
				}

				// Registering external user
				if($this->qb->notAlreadyUsed($mail)) {
					$currentUserId = $this->model->registerExternalUser(TYPE_FACEBOOK, $externalId, $mail, $firstName, $lastName, $birthDate, $location, $pictureKey);
					$currentUser = $this->qb->getUserSessionDataById($currentUserId);
				}
			}
			
			if(!is_null($currentUser)) {
				// Storing user into session
				$this->session->setCurrentUser($currentUser);
				if(!$this->qb->isUserProfileComplete($currentUser['ID'])) {
					// User Profile is not complete, redirecting to create page
					$redirectUrl = '/user/create/';
				}
				
				$this->view->showFBPage(true, $redirectUrl);
			}			    		
    	}    	    	
    }
}

?>