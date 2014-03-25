<?php
namespace HR\Core;

require PATH_DIR.'/config/smtp.ini';
require LIB_DIR.'mail/class.phpmailer.php';


class Mailer
{
    private $mail = null;
    private static $_instance = null;
    /**
     * Constructor. Must be called by constuctor of extending class
     */
    function __construct()
    {
        
        $this->mail = new \PHPMailer();

        $this->mail->Mailer = 'smtp';
        

        $this->mail->Host = BASE_SMTP_HOST;          // specify main and backup smtp server
        $this->mail->SMTPAuth = BASE_SMTP_AUTH;      // emable/disable SMTP authentication
        $this->mail->Username = BASE_SMTP_USERNAME;  // SMTP username
        $this->mail->Password = BASE_SMTP_PASSWORD;  // SMTP password
        $this->mail->From = BASE_SMTP_FROM_ADDRESS;  //FROM address
        $this->mail->FromName = BASE_SMTP_FROM_NAME;     //FROM header display name
        $this->mail->AddReplyTo('');

        $this->mail->WordWrap = 50;                                 // set word wrap to 50 characters
        $this->mail->IsHTML(true);                                  // set email format to HTML
    }
    
    
    static public function getInstance() {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    
    function addAttachment($attachment_path) {
    	if(isset($attachment_path)) {
            	$this->mail->AddAttachment($attachment_path);         	
        }
    }

    /**
     * sends an email via smtp
     *
     * @param string $to
     * @param string $subject
     * @param string $message_in_text - message in text format
     * @param string $message_in_html - message in html format
     */
    function send($to,$subject,$message_in_text,$message_in_html,$from=null,$bcc=null,$attachment_path=null) {
        $this->mail->ClearAddresses();
        
        $addressArray = array();
        $addressArray = explode(",",$to);
        
        foreach($addressArray as $to_address)
        {
        	$this->mail->AddAddress($to_address);
        }        
        $this->mail->Subject = $subject;
        $this->mail->Body = $message_in_html;                   //was url_decode($message_in_html) but was not working for cron
        $this->mail->AltBody = $message_in_text;            
        if(isset($from))
            $this->mail->From = $from;
            
        if(isset($bcc))
            $this->mail->AddBCC($bcc);
            
        if(isset($attachment_path)) {
          	$this->mail->AddAttachment($attachment_path); 
        }

        if(!(isset($_SERVER["APPLICATION_ENV"]) && $_SERVER["APPLICATION_ENV"] == 'development') && APPLICATION_ENV != 'development')
        	return $this->mail->Send();
    }


}
//EOF
?>
