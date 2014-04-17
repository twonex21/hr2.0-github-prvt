<?php
namespace HR\Auth;

use HR\Core\Model;

class AuthModel extends Model
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
}



//EOF
?>