<?php
namespace HR\Statics;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\Logger;
use HR\Core\BaseException;
use HR\Core\MySQLBeanException;

class ErrorAction extends Action implements ActionInterface {
	public function __construct() {
		parent::__construct(__NAMESPACE__);
	}
	
    function perform($reqParameters) {
        if($this->objectParams != null) {
        	
        	if($this->objectParams instanceof MySQLBeanException) {
        		Logger::writeLog($this->objectParams->getErrorMessage(), Logger::ERROR);
        	} elseif($this->objectParams instanceof BaseException) {
        		Logger::writeLog($this->objectParams->getMessage(), Logger::ERROR);
        	}
        }

    	$this->view->showErrorPage();
    }
}
//EOF
?>