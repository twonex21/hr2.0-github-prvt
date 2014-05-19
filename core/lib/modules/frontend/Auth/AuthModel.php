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

    
	public function registerExternalUser($type, $externalId, $mail, $firstName, $lastName, $birthDate, $location, $pictureKey) {    	
    	$sql = "INSERT INTO hr_user (type, external_id, mail, first_name, last_name, birth_date, location, picture_key, changed_at, created_at)
    			VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', NOW(), NOW())";
    	
    	$params = array($type, $externalId, $mail, $firstName, $lastName, $birthDate, $location, $pictureKey);
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
    
    
    public function getUserByCredentials($mail, $password) {
    	$user = array();
    	
    	$sql = "SELECT tbl.ID, tbl.password, tbl.type
				FROM
				(
					(
					SELECT user_id AS ID, mail, PASSWORD, 'USER' AS TYPE
					FROM hr_user
					)
					UNION
					(
					SELECT company_id AS ID, mail, PASSWORD, 'COMPANY' AS TYPE
					FROM hr_company
					)
				) tbl
				WHERE tbl.mail='%s'";
    	    	
    	$sql = $this->mysql->format($sql, array($mail), SQL_PREPARED_QUERY);
    	$result = $this->mysql->query($sql, SQL_PREPARED_QUERY);
    	
    	if($row = $this->mysql->getNextResult($result)) {
    		if(FrontendUtils::checkBcryptHash($password, $row['password'])) {
    			// Password is right
    			$user = $row;
    		}
    	}
    	
    	return $user;
    }
        
    
    public function externalUserExists($externalId, $type) {
    	$sql = "SELECT COUNT(*) AS count FROM hr_user WHERE type='%s' AND external_id='%s'";
    	 
    	$sql = $this->mysql->format($sql, array($type, $externalId));
    	$result = $this->mysql->query($sql);
    	 
    	return ($this->mysql->getField('count', $result) > 0);
    }
    
    
    public function setCookieInfo($entity, $cKey, $cTime) {
    	$table = 'hr_' . strtolower($entity['type']);
    	$column = strtolower($entity['type']) . '_id';
    	
    	$sql = "UPDATE %s SET ckey='%s', ctime='%s' WHERE %s=%d";
    	    	
    	$params = array($table, $cKey, $cTime, $column, $entity['ID']);
    	$sql = $this->mysql->format($sql, $params);
    	$this->mysql->query($sql);		    	
    }
}



//EOF
?>