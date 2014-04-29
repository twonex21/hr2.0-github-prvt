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
}

?>