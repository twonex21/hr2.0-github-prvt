<?php
namespace HR\Main;

use HR\Core\ActionInterface;
use HR\Core\Action;
use HR\Core\FrontendUtils;

class LoadVacancyAction extends Action implements ActionInterface 
{
    public function perform() {  	
    	$vacancyId = 0;
		$vacancy = array();
    	
		if(!$this->request->request->isNullOrEmpty('vacancyId')) {
			$vacancyId = $this->request->request->get('vacancyId');
		}
		
		$vacancy['info'] = $this->qb->getVacancyInfo($vacancyId);
		$vacancy['skills'] = $this->qb->getVacancySkills($vacancyId, 4);

    	$this->view->showBriefVacancy($vacancy);
    }
}

?>