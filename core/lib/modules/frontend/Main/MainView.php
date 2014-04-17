<?php
namespace HR\Main;

use HR\Core\View;

class MainView extends View
{	
	
	function test($encodedNumber, $data) {
		$this->assign("_encodedNumber", $encodedNumber);
		$this->assign("_data", $data);
		
        $this->render('test.tpl');
        //$this->render('test.tpl', 'CONTENT');
        //$this->finish();
	}
	
	
	function testForm() {
		$this->render('test-form.tpl');
	}	
}
//EOF
?>