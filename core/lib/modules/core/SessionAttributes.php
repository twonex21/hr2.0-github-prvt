<?php
namespace HR\Core;


class SessionAttributes implements SessionWrapperInterface
{	
	public $attributes = array();
	
	private $key;
	
	
	public function __construct($key = 'hr_attributes') {
		$this->key = $key;
	}

	
	public function getKey() {
		return $this->key;
	}
	
	
	public function initialize(array &$attributes) {
		$this->attributes = &$attributes;
	}
	
	
	public function has($key) {
		return array_key_exists($key, $this->attributes);
	}	
	
	
	public function get($key) {		
		return $this->attributes[$key];
	}
	
	
	public function set($key, $value) {
		$this->attributes[$key] = $value;		
	}

	
	public function remove($key) {		
		if(array_key_exists($key, $this->attributes)) {
			unset($this->attributes[$key]);
		}
	}
	
	
	public function &getAttributes() {
        return $this->attributes;
    }
	
    
	public function clear() {
		$this->attributes = array();
	}
}

?>
