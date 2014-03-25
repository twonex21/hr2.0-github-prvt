<?php
namespace HR\Core;

class SendMail
{

	private $view = null;
	private $mailer = null;
	
    private static $_instance = null;

    public function __construct($namespace) {
    	$this->view = new View($namespace);
    	$this->mailer = new Mailer();
    }

    static public function getInstance() {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function test($data) {
    	$this->view->assign("_data", $data);

		$text_message = $this->view->fetch('emails/test.tpl');    	
		$message_in_html = $this->view->fetch('emails/test.tpl');    	

    	$this->mailer->send($data['mail'], 'Testing', $text_message, $message_in_html, 'noreply@hr.am');
    }

    
    private function constructUnsubscribeLink($accountId, $action) {
    	// Creating 42 character hash string and putting encoded accountId in it
    	$hashString = FrontendUtils::randomString(10, false, true).FrontendUtils::fcEncode($accountId).FrontendUtils::randomString(20, false, true);
    	
    	return "http://www.favecast.com/mail/unsubscribe/?act=".strtolower($action)."&hash=".$hashString;
    }
}
?>