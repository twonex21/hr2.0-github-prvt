<?php
namespace HR\Core;


class SessionMessage implements SessionWrapperInterface
{	
	const MSG_TYPE = 'type';
	const MSG_TEXT = 'text';
	const MSG_IS_FLASH = 'isFlash';
	
	public $message = array();
	
	private $key;
	
	
	public function __construct($key = 'hr_message') {
		$this->key = $key;
	}

	
	public function getKey() {
		return $this->key;
	}
	
	
	public function initialize(array &$message) {
		$this->message = &$message;
	}
	
	
	public function isEmpty() {
		return empty($this->message);
	}
	
	
	public function get($key) {
		return $this->message[$key];
	}
			
	
	public function set($key, $value) {
		$this->message[$key] = $value;		
	}
	
	
	public function setText($text) {
		$this->message[self::MSG_TEXT] = $text;
	}
	
	
	public function setType($type) {
		$this->message[self::MSG_TYPE] = $type;
	}
	
	
	public function setFlash($isFlash) {
		$this->message[self::MSG_IS_FLASH] = $isFlash;
	}
	
	
	public function getMessage() {
		$message = $this->message;
		$this->message = array();
		
		return $message;
	}
				
		
	public function clear() {
		$this->message = array();
	}
}

?>
