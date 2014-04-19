<?php
namespace HR\User;

use HR\Core\View;

class UserView extends View
{	
	function showCreateProfilePage($userProfile, $userEducation, $userExperience, $userLanguages, $userSkills, $userSoftSkills, $allUniversities, $allUniverDegrees, $allIndustries, $allLanguages, $allLanguageLevels, $allSoftSkills, $allSoftSkillLevels) {
		$this->assign('_userProfile', $userProfile);
		$this->assign('_userEducation', $userEducation);
		$this->assign('_userExperience', $userExperience);
		$this->assign('_userLanguages', $userLanguages);
		$this->assign('_userSkills', $userSkills);
		$this->assign('_userSoftSkills', $userSoftSkills);
		$this->assign('_universities', $allUniversities);
		$this->assign('_univerDegrees', $allUniverDegrees);
		$this->assign('_industries', $allIndustries);
		$this->assign('_languages', $allLanguages);
		$this->assign('_languageLevels', $allLanguageLevels);
		$this->assign('_softSkills', $allSoftSkills);
		$this->assign('_softSkillLevels', $allSoftSkillLevels);
		
        $this->render('create.tpl', 'CONTENT');
        $this->finish();
    }
    
    function showProfilePage($userProfile, $userEducation, $userExperience, $userLanguages, $userSkills, $userSoftSkills) {
		$this->assign('_userProfile', $userProfile);
		$this->assign('_userEducation', $userEducation);
		$this->assign('_userExperience', $userExperience);
		$this->assign('_userLanguages', $userLanguages);
		$this->assign('_userSkills', $userSkills['userSkills']);
		$this->assign('_userSoftSkills', $userSoftSkills);
		
        $this->render('profile.tpl', 'CONTENT');
        $this->finish();
    }
}

?>