<?php
namespace HR\Core;

// Move in code after development finished
use \RuntimeException;
class Session
{			
	const HRSESSION = 'HRSESSION';
	const IP_KEY = 'ipAddress';
	const OBSOLETE_KEY = 'obsolete';
	const EXPIRE_KEY = 'expired';
		
	
	private $wrappers = array();
	
	private $attributeName;
	private $messageName;
	
	
	public function start() {
		ini_set('session.use_cookies', 1);
		
		if (version_compare(phpversion(), '5.4.0', '>=')) {
            session_register_shutdown();
        } else {
            register_shutdown_function('session_write_close');
        }
        
        
        session_name(self::HRSESSION);
        
		if (!session_start()) {
            throw new RuntimeException('Failed to start the session');
        }
        
        $attributes = new SessionAttributes();
        $this->attributeName = $attributes->getKey();
        $this->registerWrapper($attributes);
        
        $message = new SessionMessage();
        $this->messageName = $message->getKey();
        $this->registerWrapper($message);
        
        $this->loadSession();
        
        if(!$this->isExpired()) {        	
	        if(!$this->has(self::IP_KEY) || $this->get(self::IP_KEY) != Request::getIpAddress()) {
	        	$this->clear();
	        	$this->set(self::IP_KEY, Request::getIpAddress());
	        	$this->regenerate();
	        } elseif(rand(1, 100) <= 5) {
				$this->regenerate();
			}
        } else {
        	$this->destroy();
        }
	}
	
			
	public function has($key) {
		if(!isset($this->wrappers[$this->attributeName])) {
			return false;
		}
		
		return $this->wrappers[$this->attributeName]->has($key);
	}	

	
	public function get($key) {
		if(!isset($this->wrappers[$this->attributeName])) {
			return '';
		}
		
		return $this->wrappers[$this->attributeName]->get($key);
	}
	
	
	public function set($key, $value) {
		if(isset($this->wrappers[$this->attributeName])) {			
			$this->wrappers[$this->attributeName]->set($key, $value);
		}
	}		
	
	
	public function remove($key) {
		if(isset($this->wrappers[$this->attributeName])) {
			$this->wrappers[$this->attributeName]->remove($key);
		}
	}
	
	
	
	public function &getAttributes() {
		if(!isset($this->wrappers[$this->attributeName])) {
			return array();
		}
				
		return $this->wrappers[$this->attributeName]->getAttributes();
	}
		
	
	public function hasMessage() {
		if(!isset($this->wrappers[$this->messageName])) {
			return false;
		}
		
		return !$this->wrappers[$this->messageName]->isEmpty();
	}

	
	public function getMessage() {
		if(!isset($this->wrappers[$this->messageName])) {
			return array();
		}
		
		return $this->wrappers[$this->messageName]->getMessage();
	}

	
	public function setMessage($type, $text, $isFlash) {
		if(isset($this->wrappers[$this->messageName])) {			
			$this->wrappers[$this->messageName]->setType($type);
			$this->wrappers[$this->messageName]->setText($text);
			$this->wrappers[$this->messageName]->setFlash($isFlash);
		}
	}
	

	public function regenerate($destroy = false) {
		if($this->has(self::OBSOLETE_KEY)) {
			return '';
		}
		
		$this->set(self::OBSOLETE_KEY, true);
		$this->set(self::EXPIRE_KEY, time() + 10);
		
		$newSessionId = session_regenerate_id($destroy);

        // workaround for https://bugs.php.net/bug.php?id=61470 as suggested by David Grudl
        if ('files' === ini_get('session.save_handler')) {
            session_write_close();
            if (isset($_SESSION)) {
                $backup = $_SESSION;
                session_start();
                $_SESSION = $backup;
            } else {
                session_start();
            }

            $this->loadSession();
        }

        $this->remove(self::OBSOLETE_KEY);
        $this->remove(self::EXPIRE_KEY);

        return $newSessionId;
	}	
	
	
	public function clear() {
		foreach($this->wrappers as $wrapper) {			
			$wrapper->clear();
		}
		
		$_SESSION = array();
        
        $this->loadSession();
	}
	
	
	public function destroy() {
		$this->clear();
		$this->regenerate(true);
	}
	
	
	public function getId() {
		return session_id();
	}
	
	
	public function setId($newSessionId) {
		session_id($newSessionId);
	}
	
	
	public function getName() {
		return session_name();
	}
	
	
	public function setName($newSessionName) {
		session_name($newSessionName);
	}
	
	
	public function setCurrentUser($userData) {
		// Excluding the possibility of being authorized with both types simultaneously
		if($this->has(COMPANY)) {
			$this->remove(COMPANY);
		}
		$this->regenerate();
		$this->set(USER, $userData);
	}
	
	
	public function getCurrentUser() {
		return $this->get(USER);
	}
	
	
	public function getCurrentUserId() {
		$currentUser = $this->get(USER);
		
		if(array_key_exists('ID', $currentUser)) {
			return $currentUser['ID'];
		}
		
		return 0;
	}
	
	
	public function isUserAuthorized() {
		return ($this->has(USER) && $this->get(USER));
	}
	
	
	public function setCurrentCompany($companyData) {
		// Excluding the possibility of being authorized with both types simultaneously
		if($this->has(USER)) {
			$this->remove(USER);
		}
		$this->regenerate();
		$this->set(COMPANY, $companyData);
	}
	
	
	public function getCurrentCompany() {
		return $this->get(COMPANY);
	}
	
		
	public function getCurrentCompanyId() {
		$currentCompany = $this->get(COMPANY);
		
		if(array_key_exists('ID', $currentCompany)) {
			return $currentCompany['ID'];
		}
		
		return 0;
	}
	
	
	public function isCompanyAuthorized() {
		return ($this->has(COMPANY) && $this->get(COMPANY));
	}
	
	
	private function registerWrapper($wrapper) {
		$this->wrappers[$wrapper->getKey()] = $wrapper;
	}
	
	
	private function isExpired() {
		return ($this->has(self::OBSOLETE_KEY) && $this->has(self::EXPIRE_KEY) && $this->get(self::EXPIRE_KEY) > time());
	}
	
	
	private function loadSession() {
		$session = &$_SESSION;
		
		foreach($this->wrappers as $wrapper) {
			$key = $wrapper->getKey();
			$session[$key] = isset($session[$key]) ? $session[$key] : array();
			
			$wrapper->initialize($session[$key]);
		}
	}
	
}

?>
