<?php
namespace HR\Main;

use HR\Core\View;

class MainView extends View
{	
	
	function showHomePage($recentVacancies, $topCompanies, $topVacanciesCount, $randomIndustry) {
		$this->assign("_vacancies", $recentVacancies);
		$this->assign("_topCompanies", $topCompanies);
		$this->assign("_topVacanciesCount", $topVacanciesCount);
		$this->assign("_randomIndustry", $randomIndustry);		
		        
        $this->render('home.tpl', 'CONTENT');
        $this->finish();
	}
	
	
	function showBriefVacancy($vacancy) {
		$this->assign("_vacancy", $vacancy);
		
		$this->render('vacancy.tpl');
	}

	
	function testForm() {
		$this->render('test-form.tpl');
	}	
}
//EOF
?>