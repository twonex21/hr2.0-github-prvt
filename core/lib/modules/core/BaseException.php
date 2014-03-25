<?php
namespace HR\Core;
use \Exception as Exception;

class BaseException extends Exception
{
    protected $error_heading = '';
    protected $error_message = '';
    protected $error_code = '';
    protected $error_sql = '';

    protected $error_ticket_number = 0;

    //TODO: automatic loggin
    public function __construct($heading = '',$message,$code = '500')
    {
        parent::__construct($message, $code);
        $this->error_heading = $heading;
        $this->error_message = $message;
        $this->error_code = $code;

        $this->error_ticket_number = rand(1000,10000)."-".time();
    }

    //todo logging

    public function getErrorMessage()
    {
        return $this->error_message;
    }

    public function getErrorHeading()
    {
        return $this->get_heading;
    }

    public function getErrorCode()
    {
        return $this->error_code;
    }

    public function getErrorTicket()
    {
        return $this->error_ticket_number;
    }

    public function getErrorFile()
    {
        return $this->file;
    }

    public function getErrorTrace()
    {
        return $this->trace;
    }
}

//EOF
?>