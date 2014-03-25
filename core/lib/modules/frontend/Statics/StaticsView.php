<?php
namespace HR\Statics;

use HR\Core\View;

class StaticsView extends View
{
    function showStaticPage($tplName) { 		
        $this->render($tplName.'.tpl','CONTENT');
        $this->finish();
    } 

    function showErrorPage() { 		
        $this->render('error.tpl','CONTENT');
        $this->finish();
    }    
    
}
//EOF
?>