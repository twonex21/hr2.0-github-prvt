<?php
namespace HR\Statics;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\Logger;
use HR\Core\BaseException;
use HR\Core\MySQLBeanException;

class ErrorAction extends Action implements ActionInterface {
    function perform() {
        if($this->parameters != null) {
        	
        	if($this->parameters instanceof MySQLBeanException) {
        		Logger::writeLog($this->parameters->getErrorMessage(), Logger::ERROR);
        	} elseif($this->parameters instanceof BaseException) {
        		Logger::writeLog($this->parameters->getMessage(), Logger::ERROR);
        	}
        }

    	$this->view->showErrorPage();
    }
}
//EOF
?>