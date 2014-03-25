<?php
namespace HR\Core;

class QueryBuilder extends Model
{
       
    public function __construct($dbcon = null) {
    	parent::__construct();
    }
    
    
    public function test() {
    	$data = array();
    	$sql = "SELECT tid, name, mail FROM hr_test LIMIT 1";
    	
    	$result = $this->mysql->query($sql);
    	if($row = $this->mysql->getNextResult($result)) {
			$data = $row;
		}
		
		return $data;
    }
}

//EOF
?>