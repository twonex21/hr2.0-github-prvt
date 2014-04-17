<?php
namespace HR\Auth;

use HR\Core\ActionInterface;
use HR\Core\Action;

class LoginAction extends Action implements ActionInterface 
{
    public function perform() {
    	$userId = null;
    	$companyId = null;

    	if($this->request->query->has('uid')) {
    		$userId = $this->request->query->getInt('uid');
    		$this->session->setCurrentUser($this->qb->getUserSessionDataById($userId));
    		$this->redirect('user', 'create');
    	} elseif($this->request->query->has('cid')) {
    		$companyId = $this->request->query->getInt('cid');    		   	
    		$this->session->setCurrentCompany($this->qb->getCompanySessionDataById($companyId));
    		$this->redirect('company', 'create');
    	}
    }
}

?>