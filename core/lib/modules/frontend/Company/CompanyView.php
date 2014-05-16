<?php
namespace HR\Company;

use HR\Core\View;

class CompanyView extends View
{	
	function showCreateCompanyProfilePage($companyProfile, $companyOffices, $allBenefits, $companyBenefits) {

		//basic info
		$this->assign('_companyProfile', $companyProfile);
		$this->assign('_companyOffices', $companyOffices);
		
		//benefits
		$this->assign('_allBenefits', $allBenefits);
		$this->assign('_companyBenefits', $companyBenefits);
		
		
        $this->render('create.tpl', 'CONTENT');
        $this->finish();
    }
    
	function showCompanyProfilePage($companyProfile, $companyOffices, $allBenefits, $companyBenefits, $companyVacancies, $maxPageViews, $usersApplyedCount, $isSubscriptionForOpenings, $isWorker) {

		// Basic info
		$this->assign('_companyProfile', $companyProfile);
		$this->assign('_companyOffices', $companyOffices);
		
		// Benefits
		$this->assign('_allBenefits', $allBenefits);
		$this->assign('_companyBenefits', $companyBenefits);
		
		// Vacancies
		$this->assign('_companyVacancies', $companyVacancies);
		
		// Max page views
		$this->assign('_maxPageViews', $maxPageViews);
		
		// Users applied count
		$this->assign('_usersApplyedCount', $usersApplyedCount);
		
		// Is subscribed
		$this->assign('_isSubscriptionForOpenings', $isSubscriptionForOpenings);
		
		// Wants to work here
		$this->assign('_isWorker', $isWorker);
		
        $this->render('profile.tpl', 'CONTENT');
        $this->finish();
    }
    
    
    public function showCompanyApplicants($applicants, $pagesCount) {
    	$this->assign('_applicants', $applicants);
    	$this->assign('_pagesCount', $pagesCount);
    	
    	$this->render('applicants.tpl', 'CONTENT');
    	$this->finish();
    }
    
    
    public function showCompanyWorkers($workers, $pagesCount) {
    	$this->assign('_workers', $workers);
    	$this->assign('_pagesCount', $pagesCount);
    	
    	$this->render('workers.tpl', 'CONTENT');
    	$this->finish();
    }

}

?>