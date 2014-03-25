<?php
namespace HR\Core;

require_once LIB_DIR.'modules/core/MySQLBeanException.php';

class MySQLBean
{
    private $con = null;
    private $resultSet = null;
    private $rowCount = null;
    private $insertID = null;

    private $affectedRows = null;

    private static $_instance = null;

    protected function __construct() {
        // private constructor restricts instantiaton to getInstance()
    }

    private function __clone() {}

    static public function getInstance() {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * createConnection is a copy of __constructor.
     *
     * @access public
     */

    public function createConnection($host,$user,$pass,$db)
    {
        $this->con = mysql_connect($host,$user,$pass,true);
        if(!$this->con) {
            throw new MySQLBeanException('connect',mysql_error(),mysql_errno());
        }


        if(mysql_select_db($db) != true)
        {
            throw new MySQLBeanException('select db',mysql_error(),mysql_errno());
        }

        $this->query('set names utf8');
    }

    /**
     * Close the connection
     *
     * The function close connection is a clone of destructor, this will
     * close the connection and return true in case of success or return false
     * in case of fail.
     *
     * @param
     * @return boolean
     * @access public
     */
    public function closeConnection()
    {
        if(mysql_close($this->con)) {
            return true;
        } else {
            return false;
        }
    }

    public function isConnected()
    {
        if($this->con == null)
        {
            $this->createConnection(BASE_DB_HOST,BASE_DB_USER,
                BASE_DB_PASS,BASE_DB_DB);
        }
    }


    /**
     * Properly escapse a variable used in a query string
     * Example: $sql = 'SELECT foo FROM bar WHERE name='.e("zak's house")';
     * Adds quotes around strings automatically
     * @return string Escaped string
     */
    public function e($inputData)
    {
        $this->isConnected();

        if(!is_numeric($inputData)) {
            $inputData = mysql_real_escape_string($inputData,$this->con);
        }
        return $inputData;
    }

    /**
     * Formats a query
     * See php.net/sprintf for a list of conversion specifications
     * String input variables are escaped automatically
     * Quick reference: %d for integers, %s for strings
     * @param string $sql Input sql containing conversion specifications
     * @param array $parameterArray Array or single variable containing the
     * input variable(s) in the order they will replace the placeholders
     * @return string Properly formated query
     */
    public function format($sql,$parameterArray)
    {
        $this->isConnected();

        if(is_array($parameterArray))
        {
            foreach($parameterArray as $k => $v)
            {
                if(!is_numeric($v))
                {
                    if (get_magic_quotes_gpc()) 
                    {
                        $v = stripslashes($v);
                    }
                    else 
                    {
                        $v = mysql_real_escape_string($v,$this->con);
                    }
                    $parameterArray[$k] = $v;
                }
                
            }
            return vsprintf($sql,$parameterArray);
        } else {
            return sprintf($sql,mysql_real_escape_string($parameterArray,$this->con));
        }
    }
    
    
    public function realEscapeString($string)
    {
        $this->isConnected();
                
        return mysql_real_escape_string($string, $this->con);        
    }

    /**
     * To execute an sql query.
     *
     * The function query will be responcible for executing a sql
     * This will be take the sql as parameter and will use Con to
     * execute the query
     *
     * @param string $sql The parameter is nothing but a sql statement.
     * @return resultset This function will be returning the result set of the
     * delete query.
     * @access public
     */

    public function query($sql,$debug = true)
    {
        $this->isConnected();
        //$this->SQL = $sql;

        //echo $sql.'<br/><br />';

        //QUERY
        $this->resultSet = mysql_query($sql,$this->con);

        //if($debug == true)
        //{
        //    $this->debug($sql,$this->resultSet);
        //}

        if(!$this->resultSet) {
            throw new MySQLBeanException('query',mysql_error(),mysql_errno(),$sql);
        }

        return $this->resultSet;
    }

    public function internalQuery($sql)
    {
        //QUERY
        $resultSet = mysql_query($sql,$this->con);

        return $resultSet;
    }

    /**
     * Query a database and do not buffer the result set
     * Ideal for huge query results, do not store result set handle.
     * @param string The sql statement sent to the database server
     */
    public function unbufferedQuery($sql,$debug = false)
    {
        $this->resultSet = mysql_unbuffered_query($sql,$this->con);

        if(!$this->resultSet) {
            throw new MySQLBeanException('query',mysql_error(),mysql_errno(),$sql);
        }

        return $this->resultSet;
    }

    /**
     * Fetches one result from a database query per call. Used in a loop
     * @param ressource $result Result handle
     * @return mixed Associative array
     */
    public function getNextResult($result = null)
    {
        if($result == null)
        {
            return mysql_fetch_array($this->resultSet,MYSQL_ASSOC);
        } else {
            return mysql_fetch_array($result,MYSQL_ASSOC);
        }
    }
    

    /**
     * Get a Data Set as an array
     *
     * @param ressource $result
     * @return array
     */
    public function getDataSet($result = null)
    {
        $resultSet = array();

        while($data = $this->getNextResult($result))
        {
            $resultSet[] = $data;
        }

        return $resultSet;
    }

    /**
     * Fetch exactly one field from one row
     *
     * @param ressource $result
     * @param string $field
     * @return string the fields value
     */
    public function getField($field,$result = null)
    {
        if($result == null){
            $tmp = $this->getNextResult($result);
        } else {
            $tmp = $this->getNextResult($this->resultSet);
        }

        return $tmp[$field];
    }


    /**
     * Get the rowcount
     *
     * The function getRowCount will return the number of rows of any result
     * set, by any select query executed using this class
     *
     * @param ressource $result Result handle
     * @return int rowcount
     * @access public
     */
    public function getRowCount($result = null)
    {
        if($result == null)
        {
            $this->rowCount = mysql_num_rows($this->resultSet);
        } else {
            $this->rowCount = mysql_num_rows($result);
        }

        return $this->rowCount;
    }

    /**
     * Get the last inserted ID
     *
     * The function getInsertID will be returing the last inserted id in case
     * of executing any insert statement using this object
     *
     * @param
     * @return int inserted id
     * @access public
     */
    public function getInsertID()
    {
        $this->insertID = mysql_insert_id($this->con);

        return $this->insertID;
    }

    /**
     * Getting the result set
     *
     * The function getResultSet will return the result set array created by
     * any mysql statement
     *
     * @param
     * @return array the result set
     * @access public
     */
    public function getResultSet()
    {
        return $this->resultSet;
    }

    /**
     * Get the affectd rows
     *
     * The function getAffectedRows will return the number of rows affected by
     * any mysql statement.
     *
     * @param
     * @return int affected rows
     * @access public
     */
    public function getAffectedRows()
    {
        $this->affectedRows = mysql_affected_rows($this->con);

        return $this->affectedRows;
    }

    /**
     * Get row count generated by sql_calc_found_rows
     *
     * @param ressource result resulthandle
     * @return int row count
     */
    public function getSqlFoundRows()
    {
        $sql = 'SELECT FOUND_ROWS() AS rows';

        $res = $this->query($sql);

        $rows = $this->getField('rows',$res);

        return $rows;
    }

    /**
     * Get a next ID of a sequence
     *
     * The Function getNextID will take a sequence name as parameter and return
     * a number, meanwhile this will increment the sequence by one
     *
     * @param string SequenceName
     * @return Integer Incremented Sequence value in case of success
     * @return Integer -1 if any exception occurs
     * @access public
     */

    public function getNextID($SequenceName = 'ct_pac_squence')
    {
        $ReturnValue = 0;

        try {
            $sql = 'SELECT nextID FROM '.$SequenceName;
            $result = $this->query($sql);

            if($this->getRowCount($result))
            {
                $res = $this->getNextResult($result);

                $ReturnValue =  $res['nextID']+1;

                $sql = 'UPDATE '.$SequenceName.' set nextID=nextID + 1';
                $this->query($sql);
            } else {
                $sql = 'INSERT INTO '.$SequenceName.'(nextID) values(1)';
                $this->query($sql);
                $ReturnValue = 1;
            }
            return $ReturnValue;
        } catch(MySQLBeanException $e) {
            return -1;
        }
    }

    /**
     * Reset Sequence
     *
     * Function will reset the sequence
     *
     * @param String SequenceName name of sequence to reset
     * @return Boolean True in case of successful reset
     * @return Boolean False in case of exception
     * @access public
     */

    public function resetSequence($SequenceName)
    {
        try {
            $sql = "TRUNCATE TABLE ".$SequenceName;
            $this->query($sql);
        } catch(MySQLBeanException $e) {
            return false;
        }
        return true;
    }

    private function debug($sql,$resultset)
    {
        $xml = '';


        $xml = '<query>'."\n";
        //store original sql;
        $xml .= '<sql>'.$sql.'</sql>'."\n";

        $time = sprintf("%8.4f", 1.1);
        $xml .= '<runtime>'.$time.'</runtime>'."\n";

        if(!$resultset)
        {
            $xml .= '<status failed="true">Query failed</status>'."\n";
        } else {
            $xml .= '<status failed="false">Query succeeded</status>'."\n";
        }

        //add explain to sql select queries and query
        if(preg_match('/^(select)/isU',$sql) == 1)
        {
            $explain_sql = 'explain '.$sql;

            $res = $this->internalQuery($explain_sql);

            $xml .= '<explain>'."\n";

            while($data = $this->getNextResult($res))
            {
                $xml .= '<row id="'.$data['id'].'">';
                $xml .= '<select_type>'.$data['select_type'].'</select_type>';
                $xml .= '<table>'.$data['table'].'</table>';
                $xml .= '<type>'.$data['type'].'</type>';
                $xml .= '<possible_keys>'.$data['possible_keys'].'</possible_keys>';
                $xml .= '<key>'.$data['key'].'</key>';
                $xml .= '<key_len>'.$data['key_len'].'</key_len>';
                $xml .= '<ref>'.$data['ref'].'</ref>';
                $xml .= '<rows>'.$data['rows'].'</rows>';
                if(isset($data['Extra']))
                {
                    $xml .= '<extra>'.$data['Extra'].'</extra>';
                }
                $xml .= '</row>';

            }
            $xml .= '</explain>'."\n";

            //store data in xml
        }

        $xml .= '</query>'."\n";

        //echo $xml;

        $filename = PATH_DIR.'tools'.DIRECTORY_SEPARATOR.'query_log.xml';
        file_put_contents($filename,$xml,FILE_APPEND);
    }
}
?>