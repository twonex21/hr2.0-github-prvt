<?php
namespace HR\Core;

class Model
{
    /**
     * @var MySQLBean Holds the one instance of the MySQLBean class
     */
    //public $db = null;            

    private static $_instance;
    protected $mysql = null; //Holds the MySQLBean obje

       
    public function __construct() {
    	$this->mysql = $this->getDBConnection();
    }
    
    
    static public function getInstance() {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }        
    
    /**
     * @return MySQLBean Returns a new MySQLBean object or reuses an old one
     */
    public static function getDBConnection()
    {
        //TODO should get rid of this some time too
        require_once LIB_DIR.'modules/core/MySQLBean.php';

        return MySQLBean::getInstance();
    }

}

//EOF
?>