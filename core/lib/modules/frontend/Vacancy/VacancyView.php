<?php
namespace HR\Vacancy;

use HR\Core\View;

class VacancyView extends View
{	
	public function showOpenVacancyPage($data, $vacancy) {
		$this->assign('_data', $data);		
		$this->assign('_vacancy', $vacancy);
		
        $this->render('open.tpl', 'CONTENT');
        $this->finish();
    }
	
    
    public function showVacancyPage($vacancy, $matching, $requiredExperience, $canApply) {    	
    	$this->assign('_vacancy', $vacancy);
    	$this->assign('_matching', $matching);
    	$this->assign('_requiredExperience', $requiredExperience);
    	$this->assign('_canApply', $canApply);
    
    	$this->render('view.tpl', 'CONTENT');
    	$this->finish();
    }
	
    
    public function showVacancyManager($vacancies, $pagesCount) {    	
    	$this->assign('_vacancies', $vacancies);    	
    	$this->assign('_pagesCount', $pagesCount);    	
    
    	$this->render('manager.tpl', 'CONTENT');
    	$this->finish();
    }
}

?>