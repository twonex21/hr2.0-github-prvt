<?php
namespace HR\Auth;

use HR\Core\Model;
use HR\Core\FrontendUtils;

class AuthModel extends Model
{    	

	public function registerUser($mail, $password, $firstName, $lastName) {    	
    	$sql = "INSERT INTO hr_user (mail, password, first_name, last_name, changed_at, created_at)
    			VALUES ('%s', '%s', '%s', '%s', NOW(), NOW())";
    	
    	$params = array($mail, FrontendUtils::generateBcryptHash($password), $firstName, $lastName);
    	$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);
    	$result = $this->mysql->query($sql, SQL_PREPARED_QUERY);
		
    	$userId = $this->mysql->getInsertID();
    	 
    	return $userId;
    }

    
	public function registerCompany($name, $mail, $password, $phone, $person) {    	
    	$sql = "INSERT INTO hr_company (name, mail, password, phone, contact_person, changed_at, created_at)
    			VALUES ('%s', '%s', '%s', '%s', '%s', NOW(), NOW())";
    	
    	$params = array($name, $mail, FrontendUtils::generateBcryptHash($password), $phone, $person);
    	$sql = $this->mysql->format($sql, $params, SQL_PREPARED_QUERY);
    	$result = $this->mysql->query($sql, SQL_PREPARED_QUERY);
		
    	$companyId = $this->mysql->getInsertID();
    	 
    	return $companyId;
    }
        
}



//EOF
?>