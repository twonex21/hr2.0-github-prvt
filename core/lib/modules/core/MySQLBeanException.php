<?php
namespace HR\Core;

class MySQLBeanException extends BaseException
{
    function __construct($heading = '', $message, $code = '500', $sql = '')
    {
        parent::__construct($message, $code);
        $this->error_heading = $heading;
        $this->error_message = $message;
        $this->error_code = $code;
        $this->error_sql = $sql;
    }

    final public function getErrorMessage()
    {
        return $this->getMysqlError();
    }
    
    /**
     * Get the error message
     * @return string The error message
     */
    final public function getMySQLError()
    {
        $msg = 'Error: '.$this->error_heading.' - MySQL said: '.$this->error_message.
                ' - Code: '.$this->error_code;
        if($this->error_sql != '')
        {
                $msg .= ' - Query was: '.$this->error_sql;
        }

        $msg .= ' - Trace: <pre>'.$this->getTraceAsString().'</pre>';

        return $msg;
    }

}

//EOF
?>
