<?php
namespace HR\Support;

use HR\Core\View;

class SupportView extends View
{
    /**
     * show form
     */
    function showMessage($message, $type)
    {      
    	$this->assign("_message",$message);
		$this->assign("_type",$type);
		
		$this->render('modules/frontend/Support/templates/notification.tpl');
    }     
}
//EOF
?>