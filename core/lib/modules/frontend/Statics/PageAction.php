<?php
namespace HR\Statics;

use HR\Core\ActionInterface;
use HR\Core\Action;

class PageAction extends Action implements ActionInterface {
	public function __construct() {
		parent::__construct(__NAMESPACE__);
	}	
	
    function perform($reqParameters){	
    	$pagesArray = array("tos", "privacy", "faq", "not-found");    	        

        if(isset($reqParameters['p']) && $reqParameters['p']!="" && in_array($reqParameters['p'], $pagesArray)) {
        	$this->view->showStaticPage($reqParameters['p']);
        } else {
        	header("Location: /notfound");
        }
    }
}
//EOF
?>