<?php

class SupportView extends View
{
    /**
     * Constructs a view
     *
     */
    function __construct()
    {
        parent::__construct();
    }  
    
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