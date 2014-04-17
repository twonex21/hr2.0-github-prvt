<?php
namespace HR\Main;

use HR\Core\Model;

class MainModel extends Model
{    	

	public function test() {
    	$data = array();
    	
    	$sql = "SELECT tid, name, mail FROM hr_test WHERE name='%s'";
    	
    	$params = array('Hello World!');
    	$sql = $this->mysql->format($sql, $params);
    	
    	$result = $this->mysql->query($sql);
    	while($row = $this->mysql->getNextResult($result)) {
			$data[] = $row;
		}
		
		return $data;
    }
    
    
    public function prepareTest() {
    	$data = array();
    	
    	$sql = "SELECT name FROM hr_test WHERE name='%s' AND tid=%d";
    	$params = array('Hello World!', 1);
    	
    	$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);
    	$result = $this->mysql->query($sql, SQL_PREPARED_QUERY);
    	
    	while($row = $this->mysql->getNextResult($result)) {
			$data[] = $row;
		}
		
		print_r($data);
		return $data;
    }
}



//EOF
?>