<?php
namespace HR\Core;

// Move in code after development finished
use \InvalidArgumentException;

class RequestWrapper
{			
	protected $parameters;
	
	public function __construct($parameters = array()) {
		$this->parameters = $parameters;		
	}
	
	
	public function isEmpty() {
		return empty($this->parameters);
	}
	
	
	public function getParameters() {
		return $this->parameters;
	}
	
	
	public function setParameters($parameters) {
		return $this->parameters = $parameters;
	}
	
	
	public function addParameters($parameters) {	
        $this->parameters = array_replace($this->parameters, $parameters);    
	}
	
	
	public function get($key, $default = null, $deep = false) {
        if (!$deep || false === $pos = strpos($key, '[')) {
            return array_key_exists($key, $this->parameters) ? $this->parameters[$key] : $default;
        }

        $root = substr($key, 0, $pos);
        if (!array_key_exists($root, $this->parameters)) {
            return $default;
        }

        $value = $this->parameters[$root];
        $currentKey = null;
        for ($i = $pos, $c = strlen($key); $i < $c; $i++) {
            $char = $key[$i];

            if ('[' === $char) {
                if (null !== $currentKey) {
                    throw new InvalidArgumentException(sprintf('Malformed path. Unexpected "[" at position %d.', $i));
                }

                $currentKey = '';
            } elseif (']' === $char) {
                if (null === $currentKey) {
                    throw new InvalidArgumentException(sprintf('Malformed path. Unexpected "]" at position %d.', $i));
                }

                if (!is_array($value) || !array_key_exists($currentKey, $value)) {
                    return $default;
                }

                $value = $value[$currentKey];
                $currentKey = null;
            } else {
                if (null === $currentKey) {
                    throw new InvalidArgumentException(sprintf('Malformed path. Unexpected "%s" at position %d.', $char, $i));
                }

                $currentKey .= $char;
            }
        }

        if (null !== $currentKey) {
            throw new InvalidArgumentException(sprintf('Malformed path. Path must end with "]".'));
        }

        return $value;
    }
    
    
    public function set($key, $value) {
    	$this->parameters[$key] = $value;
    }
    
    
    public function has($key) {
    	return array_key_exists($key, $this->parameters);
    }
    
    
    public function remove($key) {
    	unset($this->parameters[$key]);
    }
    
    
    public function getInt($key, $default = 0, $deep = false) {
    	return (int) $this->get($key, $default, $deep);
    }
    
    
    public function isNullOrEmpty($key) {    	
    	if(isset($this->parameters[$key])) {
    		if(!is_array($this->parameters[$key])) $this->parameters[$key] = trim($this->parameters[$key]);
    		return empty($this->parameters[$key]);
    	}
    	
    	return true;
    }
    
    
    public function isArray($key) {
    	return is_array($this->parameters[$key]);
    }
    
    public function isDigit($key) {
    	return ctype_digit($this->parameters[$key]);
    }
}
?>
