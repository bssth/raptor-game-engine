<?php
	/** 
	 * Solution for testing if there is no access to Memcache
	 */
	 
	namespace Database\Helpers;
	
	class Filecache
	{
		private $data = array();
		
		public function __call($k, $v) { return false; }
		
		public function delete($k) { 
			unset($data[$k]);
			return true;
		}
		
		public function flush() { 
			$this->data = array(); 
			return true;
		}
		
		public function get($key)
		{
			return isset($this->data[$key]) ? $this->data[$key] : null;
		}
		
		public function replace($key, $var)
		{
			return $this->set($key, $var);
		}
		
		public function set($key, $value, $flag = null, $expire = 0)
		{
			$this->data[$key] = $value;
			return true;
		}
	}