<?php 
	
	class RaptorScratch {
		protected $vars;
		function __construct() {
			$this->vars = array();
		}
		function get($key) {
			return $this->__get($key);
		}
		function set($key, $val) {
			return $this->__set($key, $value);
		}
		function __set($key, $val) {
			$this->vars[$key] = $val;
		}
		function __get($key) {
			return $this->vars[$key];
		}
	}
	
?>