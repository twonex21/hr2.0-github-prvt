<?php
namespace HR\Main;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class HomeAction extends Action implements ActionInterface 
{
    public function perform() {  	
    	$recentVacancies = array();
    	$topCompanies = array();
    	$randomIndustry = array();
    	$topVacanciesCount = 0;
    	    	    	
    	$this->setPageTitle(PT_HOME);    	
    	
    	$recentVacancies = $this->model->getRecentVacancies(30);
    	$topCompanies = $this->model->getTopCompanies();
    	$randomIndustry = $this->model->getRandomIndustry();    	

    	$this->view->showHomePage($recentVacancies, $topCompanies, $topVacanciesCount, $randomIndustry);
    }
}

?>