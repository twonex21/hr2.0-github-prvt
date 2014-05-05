<?php
namespace HR\Auth;

use HR\Core\ActionInterface;
use HR\Core\Action;

class OutAction extends Action implements ActionInterface 
{
    public function perform() {
    	$this->session->destroy();
    	
    	$this->fc->redirect('main', 'home');
    }
}

?>