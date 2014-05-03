<?php
namespace HR\Main;

use HR\Core\Model;

class MainModel extends Model
{    	

	public function getRecentVacancies($count) {
    	$vacancies = array();
    	
    	$sql = "SELECT v.vacancy_id AS vacancyId, v.company_id AS companyId, v.title, c.name AS companyName, DATE_FORMAT(v.deadline, '%%d %%M, %%Y') AS deadline
    			FROM hr_vacancy v
    			INNER JOIN hr_company c ON v.company_id=c.company_id
    			WHERE status='%s'
    			ORDER BY v.created_at DESC
    			LIMIT %d";
    	
    	$sql = $this->mysql->format($sql, array(VACANCY_STATUS_ACTIVE, $count));    	
    	$result = $this->mysql->query($sql);    	
		$vacancies = $this->mysql->getDataSet($result);
    	
		return $vacancies;
    }
    
    
	public function getTopCompanies() {
    	$companies = array();
    	
    	$sql = "SELECT c.company_id AS companyId, c.name, c.logo_key AS logoKey,
				       (SELECT COUNT(v.vacancy_id) FROM hr_vacancy v WHERE v.company_id=c.company_id AND v.views > 0) AS vacanciesCount
				FROM hr_company c
				WHERE c.is_top=1";
    	    	    
    	$result = $this->mysql->query($sql);    	
		$companies = $this->mysql->getDataSet($result);    	
		
		return $companies;
    }
    
    
    public function getRandomIndustry() {
    	$industry = array();
    	
    	$sql = "SELECT tbl.*
				FROM
				(
					SELECT i.industry_id AS industryId, i.name, 
					       ROUND(100 * 
    							(SELECT COUNT(DISTINCT v.vacancy_id) FROM hr_vacancy v WHERE v.industry_id=i.industry_id AND v.views > 0) / 
    							(SELECT COUNT(v.vacancy_id) FROM hr_vacancy v WHERE v.views > 0)
    						) AS percentage
					FROM hr_industry i
				) tbl
				WHERE tbl.percentage > 5    			
				ORDER BY RAND()
				LIMIT 1";    	    	
    	$result = $this->mysql->query($sql);    	
    	$industry = $this->mysql->getRow($result);
    	    	
    	return $industry; 
    }
    
    
    public function getTopVacanciesCount() {
    	$count = 0;
    	
    	$sql = "SELECT COUNT(v.vacancy_id) AS count
    			FROM hr_vacancy v
    			INNER JOIN hr_company c ON c.company_id=v.company_id
    			WHERE c.is_top=1";
    	
    	$result = $this->mysql->query($sql);    	 
    	$count = $this->mysql->getField('count', $result);
    	
    	return $count;
    }
}



//EOF
?>