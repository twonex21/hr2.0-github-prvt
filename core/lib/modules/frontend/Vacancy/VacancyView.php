<?php
namespace HR\Vacancy;

use HR\Core\View;

class VacancyView extends View
{	
	function showOpenVacancyPage($allIndustries, $allUniverDegrees, $allLanguages, $allLanguageLevels, $allSoftSkills, $allSoftSkillLevels, $companyBenefits, $vacancyInfo, $vacancyEducation, $vacancyExperience, $vacancyLanguages, $vacancySkills, $vacancySoftSkills, $vacancyBenefits) {
		$this->assign('_industries', $allIndustries);
		$this->assign('_univerDegrees', $allUniverDegrees);	
		$this->assign('_languages', $allLanguages);
		$this->assign('_languageLevels', $allLanguageLevels);
		$this->assign('_softSkills', $allSoftSkills);
		$this->assign('_softSkillLevels', $allSoftSkillLevels);
		$this->assign('_benefits', $companyBenefits);
		$this->assign('_vacancyInfo', $vacancyInfo);
		$this->assign('_vacancyEducation', $vacancyEducation);
		$this->assign('_vacancyExperience', $vacancyExperience);
		$this->assign('_vacancyLanguages', $vacancyLanguages);
		$this->assign('_vacancySkills', $vacancySkills);
		$this->assign('_vacancySoftSkills', $vacancySoftSkills);
		$this->assign('_vacancyBenefits', $vacancyBenefits);
		
        $this->render('open.tpl', 'CONTENT');
        $this->finish();
    }    
}

?>