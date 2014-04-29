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
    
    public function sendApplicationMessage($vacancy, $applicantName, $resumePath) {
    	$this->view->assign("_vacancy", $vacancy);
    	$this->view->assign("_applicantName", $applicantName);

		$text_message = $this->view->fetch('emails/application_text.tpl');    	
		$message_in_html = $this->view->fetch('emails/application.tpl');    	

		if($resumePath != '') {
			// Attaching user CV here as well
			$this->mailer->addAttachment($resumePath);
		}
				
    	$this->mailer->send($vacancy['companyMail'], 'New Applicant', $text_message, $message_in_html, 'noreply@hr.am');
    }

    
    private function constructUnsubscribeLink($accountId, $action) {
    	// Creating 42 character hash string and putting encoded accountId in it
    	$hashString = FrontendUtils::randomString(10, false, true).FrontendUtils::fcEncode($accountId).FrontendUtils::randomString(20, false, true);
    	
    	return "http://www.favecast.com/mail/unsubscribe/?act=".strtolower($action)."&hash=".$hashString;
    }
}
?>