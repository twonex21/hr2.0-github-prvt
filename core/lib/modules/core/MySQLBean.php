<?php
namespace HR\Core;

use \InvalidArgumentException;

class MySQLBean
{
    private $mysqli = null;
    private $stmt = null;
    private $bindTypes = null;
    private $bindParameters = null;
    private $resultSet = null;
    private $rowCount = null;
    private $insertID = null;
    
    private static $printfChars = array('%d', "'%s'", '%f');	
    // Includes %d, %f, '%s' but not %%d
    private static $printfPatterns = array("/([^%])(('%{1}s')|(%{1}[d,f,s]))/", "/(%+)/");
    private static $mysqliReplacements = array('$1?', '%');
    private static $sqlFormatMap = array('%d' => 'i', "'%s'" => 's', '%f' => 's');

    private $affectedRows = null;

    private static $_instance = null;

    protected function __construct() {
        // private constructor restricts instantiaton to getInstance()
    }

    private function __clone() {}

    static public function getInstance() {
        if(is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * createConnection is a copy of __constructor.
     *
     * @access public
     */

    public function createConnection($host, $user, $pass, $db) {        
        $this->mysqli = new \mysqli($host, $user, $pass);
        if(!$this->mysqli || $this->mysqli->connect_errno) {
            throw new MySQLBeanException('connect', $this->mysqli->connect_error, $this->mysqli->connect_errno);
        }

        if($this->mysqli->select_db($db) != true) {
            throw new MySQLBeanException('select db', $this->mysqli->error, $this->mysqli->errno);
        }

        $this->query('SET NAMES utf8');
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
    public function closeConnection() {
        if($this->mysqli->close()) {
            return true;
        } else {
            return false;
        }
    }

    
    /**
     * Checking database connection and creating one in case there is no connection     
     */
    public function isConnected() {
        if($this->mysqli == null || $this->mysqli->connect_errno) {
            $this->createConnection(BASE_DB_HOST, BASE_DB_USER, BASE_DB_PASS, BASE_DB_DB);
        }
    }


    /**
     * Properly escapes a variable used in a query string
     * Example: $sql = 'SELECT foo FROM bar WHERE name='.e("zak's house")';
     * Adds quotes around strings automatically
     * @return string Escaped string
     */
    public function realEscapeString($inputData) {
        $this->isConnected();

        if(!is_numeric($inputData)) {
            $inputData = $this->mysqli->real_escape_string($inputData);
        }
        
        return $inputData;
    }

    /**
     * Formats a query
     * See php.net/sprintf for a list of conversion specifications
     * String input variables are escaped automatically
     * Quick reference: %d for integers, %s for strings
     * @param string $sql Input sql containing conversion specifications
     * @param array $parameters Array or single variable containing the
     * input variable(s) in the order they will replace the placeholders
     * @return string Properly formated query
     */
    public function format($sql, $parameters, $queryType = SQL_UNPREPARED_QUERY) {
        $this->isConnected();

        switch($queryType) {
        	case SQL_UNPREPARED_QUERY :
        		if(is_array($parameters)) {
		            foreach($parameters as $key => $value) {
		                if(!is_numeric($value)) {
		                    if (get_magic_quotes_gpc()) {
		                        $value = stripslashes($value);
		                    }                                                           
		                    $parameterArray[$key] = $this->mysqli->real_escape_string($value);
		                }
		            }
	            	return vsprintf($sql, $parameters);
		        } else {
		            return sprintf($sql, $this->mysqli->real_escape_string($parameters));
		        }
		        break;
	        case SQL_PREPARED_QUERY :
	        	$this->bindTypes = '';
	        	$this->bindParameters = array();
	        	$pos = 0;
	        	$counter = 0;

	        	while(($posArray = FrontendUtils::strposa($sql, self::$printfChars, $pos, '%')) !== false) {	        	
	        		if(array_key_exists($counter, $parameters)) {
		        		$this->bindTypes .= self::$sqlFormatMap[$posArray[1]];
		        		$this->bindParameters[] = $parameters[$counter++];
		        		$pos = $posArray[0] + 1;
	        		} else {
	        			throw new InvalidArgumentException('Wrong SQL string format or no parameters passed.');
	        		}
	        	}
	        	// Replacing printf characters with ? for prepared query execution, and not matching %% escaped characters
	        	return preg_replace(self::$printfPatterns, self::$mysqliReplacements, $sql);
        }            
    }
    

    /**
     * To execute an sql query.
     *
     * The function query will be responsible for executing a sql
     * This will be take the sql as parameter and will use Con to
     * execute the query
     *
     * @param string $sql The parameter is nothing but a sql statement.
     * @return resultset This function will be returning the result set of the
     * delete query.
     * @access public
     */
    public function query($sql, $queryType = SQL_UNPREPARED_QUERY, $debug = true) {
        $this->isConnected();

        switch($queryType) {
        	case SQL_UNPREPARED_QUERY :        
	        	// Executing query
	        	if(($this->resultSet = $this->mysqli->query($sql)) != true) {
		            throw new MySQLBeanException('query', $this->mysqli->error, $this->mysqli->errno, $sql);
		        }		        
		        /*
		        if($debug == true) $this->debug($sql, $this->resultSet);
		        */
		        break;
	        case SQL_PREPARED_QUERY :
	        	// Preparing query
	        	if(!$this->stmt = $this->mysqli->prepare($sql)) {
	        		throw new MysqlBeanException('prepare query', $this->mysqli->error, $this->mysqli->errno, $sql);
	        	} 
	        	// Binding parameters       	
	        	$this->bindParameters();
	        	// Executing prepared query
	        	if(!$this->stmt->execute()) {
	        		throw new MySQLBeanException('execute', $this->mysqli->error, $this->mysqli->errno, $sql);
	        	}        	
	        	// Getting results of executed query
	        	$this->resultSet = $this->stmt->get_result();	
        }

        return $this->resultSet;
    }

    
    /**
     * Binds variables to a prepared statement as parameters
     *     
     * @access public
     */
    public function bindParameters() {
    	if($this->bindTypes != null && $this->bindParameters != null && strlen($this->bindTypes) == count($this->bindParameters)) {
    		$bindArray = array_merge(array($this->bindTypes), $this->bindParameters);
    		if(version_compare(phpversion(), '5.3', '>=')) {
    			$bindArray = FrontendUtils::getRefArray($bindArray);
    		}
    		
    		$bindMethod = new \ReflectionMethod('mysqli_stmt', 'bind_param');
    		$bindMethod->invokeArgs($this->stmt, $bindArray);
    	} else {
    		throw new InvalidArgumentException('Wrong SQL string format or no parameters passed.');
    	}
    }
    
    
    /**
     * Query a database and do not buffer the result set
     * Ideal for huge query results, do not store result set handle.
     * @param string The sql statement sent to the database server
     */
    public function unbufferedQuery($sql, $debug = false) {
        $this->resultSet = $this->mysqli->query($sql, MYSQLI_USE_RESULT);

        if(!$this->resultSet) {
            throw new MySQLBeanException('query', $this->mysqli->error, $this->mysqli->errno, $sql);
        }

        return $this->resultSet;
    }
    
    
    /**
     * Close the result set
     *
     * @param ressource $result
     */
    public function closeResultSet($result = null) {
    	if($result == null) {    		
    		$this->resultSet->close();
    	} elseif($result instanceof \mysqli_result) {
    		$result->close();
    	} else {
    		throw new MySQLBeanException('result', $this->mysqli->error, $this->mysqli->errno, $sql);
    	}
    }
    

    /**
     * Fetches one result from a database query per call. Used in a loop
     * @param ressource $result Result handle
     * @return mixed Associative array
     */
    public function getNextResult($result = null) {
        if($result == null) {        	
            return $this->resultSet->fetch_array(MYSQLI_ASSOC);
        } elseif($result instanceof \mysqli_result) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else {
        	throw new MySQLBeanException('query', $this->mysqli->error, $this->mysqli->errno, $sql);
        }
    }
    

    /**
     * Get a Data Set as an array
     *
     * @param ressource $result
     * @return array
     */
    public function getDataSet($result = null) {
        $dataSet = array();

        if($result == null) {        	
            while($data = $this->getNextResult($this->resultSet)) {
	            $dataSet[] = $data;
	        }
        } elseif($result instanceof \mysqli_result) {
            while($data = $this->getNextResult($result)) {
	            $dataSet[] = $data;
	        }
        } else {
        	throw new MySQLBeanException('query', $this->mysqli->error, $this->mysqli->errno, $sql);
        }   
             
        return $dataSet;
    }
    
    
    /**
     * Get a Row as an array
     *
     * @param ressource $result
     * @return array
     */
    public function getRow($result = null) {        
    	if($result == null) {        	
            if($row = $this->getNextResult($this->resultSet)) {
	            return $row;
	        }
        } elseif($result instanceof \mysqli_result) {
            if($row = $this->getNextResult($result)) {
	            return $row;
	        }
        } else {
        	throw new MySQLBeanException('query', $this->mysqli->error, $this->mysqli->errno, $sql);
        } 
        
        return array();
    }

    
    /**
     * Fetch exactly one field from one row
     *
     * @param ressource $result
     * @param string $field
     * @return string the fields value
     */
    public function getField($field, $result = null) {
        if($result == null) {
            $tmp = $this->getNextResult($result);
            return $tmp[$field];
        } elseif($result instanceof \mysqli_result) {
            $tmp = $this->getNextResult($this->resultSet);
            return $tmp[$field];
        } else {
        	throw new MySQLBeanException('query', $this->mysqli->error, $this->mysqli->errno, $sql);
        }

        return false;
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
    public function getRowCount($result = null) {
        if($result == null) {
            $this->rowCount = $this->resultSet->num_rows;
            return $this->rowCount;
        } elseif($result instanceof \mysqli_result) {
            $this->rowCount = $result->num_rows;
            return $this->rowCount;
        } else {
        	throw new MySQLBeanException('query', $this->mysqli->error, $this->mysqli->errno, $sql);
        }   
        
        return false;     
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
    public function getInsertID() {
        $this->insertID = $this->mysqli->insert_id;

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
    public function getResultSet() {
        return $this->resultSet;
    }

    
    /**
     * Get the affected rows
     *
     * The function getAffectedRows will return the number of rows affected by
     * any mysql statement.
     *
     * @param
     * @return int affected rows
     * @access public
     */
    public function getAffectedRows() {
        $this->affectedRows = $this->mysqli->affected_rows;

        return $this->affectedRows;
    }

    
    /**
     * Get row count generated by sql_calc_found_rows
     *
     * @param ressource result resulthandle
     * @return int row count
     */
    public function getSqlFoundRows() {
        $sql = 'SELECT FOUND_ROWS() AS rows';

        $result = $this->query($sql);
        $rows = $this->getField('rows', $result);

        return $rows;
    }
    
    
    private function debug($sql,$resultset) {
        $xml = '';

        $xml = '<query>'."\n";
        //store original sql;
        $xml .= '<sql>'.$sql.'</sql>'."\n";

        $time = sprintf("%8.4f", 1.1);
        $xml .= '<runtime>'.$time.'</runtime>'."\n";

        if(!$resultset) {
            $xml .= '<status failed="true">Query failed</status>'."\n";
        } else {
            $xml .= '<status failed="false">Query succeeded</status>'."\n";
        }

        //add explain to sql select queries and query
        if(preg_match('/^(select)/isU', $sql) == 1) {
            $explain_sql = 'explain ' . $sql;

            $res = $this->internalQuery($explain_sql);

            $xml .= '<explain>'."\n";

            while($data = $this->getNextResult($res)) {
                $xml .= '<row id="'.$data['id'].'">';
                $xml .= '<select_type>'.$data['select_type'].'</select_type>';
                $xml .= '<table>'.$data['table'].'</table>';
                $xml .= '<type>'.$data['type'].'</type>';
                $xml .= '<possible_keys>'.$data['possible_keys'].'</possible_keys>';
                $xml .= '<key>'.$data['key'].'</key>';
                $xml .= '<key_len>'.$data['key_len'].'</key_len>';
                $xml .= '<ref>'.$data['ref'].'</ref>';
                $xml .= '<rows>'.$data['rows'].'</rows>';
                if(isset($data['Extra'])) {
                    $xml .= '<extra>'.$data['Extra'].'</extra>';
                }
                $xml .= '</row>';
            }
            $xml .= '</explain>'."\n";
        }

        $xml .= '</query>'."\n";

        $filename = PATH_DIR . 'tools' . DIRECTORY_SEPARATOR . 'query_log.xml';
        file_put_contents($filename, $xml, FILE_APPEND);
    }
    
    
    public function __destruct() {
    	if($this->mysqli != null && !$this->mysqli->errno) {    		
    		if($this->stmt != null) {
    			$this->stmt->close();
    		}
    		$this->closeConnection();
    	}
    }
}
?>