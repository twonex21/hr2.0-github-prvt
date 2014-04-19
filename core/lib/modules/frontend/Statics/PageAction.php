<?php
namespace HR\Statics;

use HR\Core\ActionInterface;
use HR\Core\Action;

class PageAction extends Action implements ActionInterface {	
    function perform(){	
    	$pagesArray = array("tos", "privacy", "faq", "not-found", "no-access");    	        

        if(!$this->request->query->isNullOrEmpty('p') && in_array($this->request->query->get('p'), $pagesArray)) {
        	$this->view->showStaticPage($this->request->query->get('p'));
        } else {
        	$this->fc->delegateNotFound();        	
        }
    }
}
//EOF
?>