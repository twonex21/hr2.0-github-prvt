<?php
namespace HR\Search;

use HR\Core\Model;
use HR\Core\FrontendUtils;

class SearchModel extends Model
{    	
	public function getVacancySearchResults($keyword, $count, $user) {
    	$vacancies = array();
    	
    	$strictMatch = '';
    	$fullMatch = '';
    	$keywords = explode(' ', $keyword);
    	if(count($keywords) > 1) {
    		$strictMatch = '"' . $keyword . '"';
    		$fullMatch = '+' . implode(' +', $keywords);
    	}
    	
    	$sql = "SELECT 	tbl.weight, tbl.vacancyId, tbl.title, tbl.companyId, c.name AS companyName
				FROM
				(
					(
					SELECT 6 AS weight, v.vacancy_id AS vacancyId, v.title, v.company_id AS companyId
					FROM hr_vacancy v
					INNER JOIN hr_vacancy_search vs ON vs.vacancy_id=v.vacancy_id
					WHERE MATCH (vs.skills) AGAINST ('%3\$s' IN BOOLEAN MODE) AND v.status='%4\$s'
					GROUP BY v.vacancy_id
					)	
					UNION
					(
					SELECT 5, v.vacancy_id AS vacancyId, v.title, v.company_id AS companyId
					FROM hr_vacancy v
					INNER JOIN hr_vacancy_search vs ON vs.vacancy_id=v.vacancy_id
					WHERE MATCH (vs.title) AGAINST ('%2\$s' IN BOOLEAN MODE) AND v.status='%4\$s'
					GROUP BY v.vacancy_id
					)
					UNION
					(
					SELECT 4, v.vacancy_id AS vacancyId, v.title, v.company_id AS companyId
					FROM hr_vacancy v
					INNER JOIN hr_vacancy_search vs ON vs.vacancy_id=v.vacancy_id
					WHERE MATCH (vs.company_name, vs.location) AGAINST ('%3\$s' IN BOOLEAN MODE) AND v.status='%4\$s'
					GROUP BY v.vacancy_id
					)
					UNION 
					(
					SELECT 3 AS weight, v.vacancy_id AS vacancyId, v.title, v.company_id AS companyId
					FROM hr_vacancy v
					INNER JOIN hr_vacancy_search vs ON vs.vacancy_id=v.vacancy_id
					WHERE MATCH (vs.skills) AGAINST ('%1\$s') AND v.status='%4\$s'
					GROUP BY v.vacancy_id		
					)
					UNION
					(
					SELECT 2 AS weight, v.vacancy_id AS vacancyId, v.title, v.company_id AS companyId
					FROM hr_vacancy v
					INNER JOIN hr_vacancy_search vs ON vs.vacancy_id=v.vacancy_id
					WHERE MATCH (vs.title) AGAINST ('%1\$s') AND v.status='%4\$s'
					GROUP BY v.vacancy_id	
					)
					UNION
					(
					SELECT 2 AS weight, v.vacancy_id AS vacancyId, v.title, v.company_id AS companyId
					FROM hr_vacancy v
					INNER JOIN hr_vacancy_search vs ON vs.vacancy_id=v.vacancy_id
					WHERE MATCH (vs.title) AGAINST ('%1\$s') AND v.status='%4\$s'
					GROUP BY v.vacancy_id	
					)
					UNION
					(
					SELECT 1 AS weight, v.vacancy_id AS vacancyId, v.title, v.company_id AS companyId
					FROM hr_vacancy v
					INNER JOIN hr_vacancy_search vs ON vs.vacancy_id=v.vacancy_id
					WHERE MATCH (vs.info) AGAINST ('%1\$s') AND v.status='%4\$s'
					GROUP BY v.vacancy_id	
					)
				) tbl
				INNER JOIN hr_company c ON tbl.companyId=c.company_id
				GROUP BY tbl.vacancyId
    			LIMIT %5\$s";
    	
    	$sql = $this->mysql->format($sql, array($keyword, $strictMatch, $fullMatch, VACANCY_STATUS_ACTIVE, $count));    	
    	$result = $this->mysql->query($sql);
    	while($row = $this->mysql->getNextResult($result)) {
    		if(!empty($user)) {
	    		$vacancy = array();
	    		// TODO: Think about getting all user or vacancy data in one call
	    		$vacancy['education'] = $this->qb->getVacancyEducation($row['vacancyId']);
	    		$vacancy['experience'] = $this->qb->getVacancyExperience($row['vacancyId']);
	    		$vacancy['languages'] = $this->qb->getVacancyLanguages($row['vacancyId']);
	    		$vacancy['skills'] = $this->qb->getVacancySkills($row['vacancyId']);
	    		$vacancy['softSkills'] = $this->qb->getVacancySoftSkills($row['vacancyId']);
	    		
	    		$row['matching'] = FrontendUtils::calculateMatching($user, $vacancy);
    		}
    		
			$vacancies[] = $row;
    	}
    	
		return $vacancies;
    }       

    
	public function getUserSearchResults($keyword, $count, $vacancies) {
    	$users = array();
    	
    	$strictMatch = '';
    	$fullMatch = '';
    	$keywords = explode(' ', $keyword);
    	if(count($keywords) > 1) {
    		$strictMatch = '"' . $keyword . '"';
    		$fullMatch = '+' . implode(' +', $keywords);
    	}
    	
    	$sql = "SELECT 	tbl.weight, tbl.user_id AS userId, tbl.name, tbl.experience, tbl.location, u.picture_key AS pictureKey
				FROM
				(
					(
					SELECT 6 AS weight, us.user_id, us.name, us.location, us.experience
					FROM hr_user_search us
					WHERE MATCH (us.name) AGAINST ('%3\$s' IN BOOLEAN MODE)
					)
					UNION
					(
					SELECT 5 AS weight, us.user_id, us.name, us.location, us.experience
					FROM hr_user_search us
					WHERE MATCH (us.skills) AGAINST ('%3\$s' IN BOOLEAN MODE)
					)
					UNION
					(
					SELECT 4 AS weight, us.user_id, us.name, us.location, us.experience
					FROM hr_user_search us
					WHERE MATCH (us.experience) AGAINST ('%2\$s' IN BOOLEAN MODE)					
					)
					UNION
					(
					SELECT 3 AS weight, us.user_id, us.name, us.location, us.experience
					FROM hr_user_search us
					WHERE MATCH (us.skills) AGAINST ('%1\$s')
					)
					UNION
					(
					SELECT 2 AS weight, us.user_id, us.name, us.location, us.experience
					FROM hr_user_search us
					WHERE MATCH (us.experience) AGAINST ('%1\$s')
					)
					UNION
					(
					SELECT 1 AS weight, us.user_id, us.name, us.location, us.experience
					FROM hr_user_search us
					WHERE MATCH (us.location) AGAINST ('%1\$s' IN BOOLEAN MODE)
					)
				) tbl
    			INNER JOIN hr_user u ON u.user_id=tbl.user_id
				GROUP BY tbl.user_id    			
    			LIMIT %4\$s";
    	
    	$sql = $this->mysql->format($sql, array($keyword, $strictMatch, $fullMatch, $count));    	
    	$result = $this->mysql->query($sql);
    	while($row = $this->mysql->getNextResult($result)) {
    		if(!empty($vacancies)) {
	    		$user = array();
	    		// TODO: Think about getting all user or vacancy data in one call
	    		$user['education'] = $this->qb->getUserEducation($row['userId']);
				$user['experience'] = $this->qb->getUserExperience($row['userId']);
				$user['languages'] = $this->qb->getUserLanguages($row['userId']);
				$user['skills'] = $this->qb->getUserSkills($row['userId']);
				$user['softSkills'] = $this->qb->getUserSoftSkills($row['userId']);
	    		
	    		$maxMatching = 0;
				foreach($vacancies as $vacancy) {					
	    			$row['matching'] = FrontendUtils::calculateMatching($user, $vacancy);
	    			if($row['matching'] > $maxMatching) {
	    				$maxMatching = $row['matching'];
	    				$row['matching']['vacancy'] = $vacancy['title'];
	    			}
				}
    		}
    		
    		$row['idHash'] = FrontendUtils::hrEncode($row['userId']);
			$users[] = $row;
    	}
    	
		return $users;
    }        
}

?>