<?php
namespace HR\Auth;

use HR\Core\View;

class AuthView extends View
{	
	public function showSignupPage() {		
        $this->render('signup.tpl');        
    }
    
	public function showUserSignupForm() {		
        $this->render('signup-user.tpl');
    }
    
	public function showCompanySignupForm() {		
        $this->render('signup-company.tpl');
    }
    
    public function showSigninPage($fbUrl) {
    	$this->assign('_fbUrl', $fbUrl);
    	
    	$this->render('signin.tpl');
    }
    
    
    public function showFBPage($success = true, $redirectUrl = '') {
    	$this->assign('_success', $success);
    	$this->assign('_redirectUrl', $redirectUrl);
    	
    	$this->render('fb-success.tpl');
    }
}

?>