<?php
namespace HR\Auth;

use HR\Core\ActionInterface;
use HR\Core\Action;

class OutAction extends Action implements ActionInterface 
{
    public function perform() {
    	// Destroying authorization cookie
    	setcookie(COOKIE_NAME, '', time()-10, '/');
    	
    	// Destroying session
    	$this->session->destroy();    	
    	
    	$this->fc->redirect('main', 'home');
    }
}

?>