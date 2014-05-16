<?php
namespace HR\User;

use HR\Core\View;

class UserView extends View
{	
	public function showCreateProfilePage($data, $user) {		
		$this->assign('_data', $data);
		$this->assign('_user', $user);
		
        $this->render('create.tpl', 'CONTENT');
        $this->finish();
    }
    
    
    public function showProfilePage($user) {
		$this->assign('_user', $user);		
		
        $this->render('profile.tpl', 'CONTENT');
        $this->finish();
    }
    
    
    public function showUserApplications($applications, $pagesCount) {
    	$this->assign('_applications', $applications);
    	$this->assign('_pagesCount', $pagesCount);
    	
    	$this->render('applications.tpl', 'CONTENT');
    	$this->finish();
    }
}

?>