<?php
namespace HR\Core;


interface SessionWrapperInterface
{	
	public function getKey();

	public function initialize(array &$array);
	
	public function clear();
}

?>
