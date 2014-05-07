<?php
namespace HR\Search;

use HR\Core\View;

class SearchView extends View
{	
	
	function showVacancySearchResults($searchResults) {
		$this->assign("_searchResults", $searchResults);	
		        
        $this->render('vacancy-results.tpl');
	}
		
	function showUserSearchResults($searchResults) {
		$this->assign("_searchResults", $searchResults);	

        $this->render('user-results.tpl');
	}	
}
?>