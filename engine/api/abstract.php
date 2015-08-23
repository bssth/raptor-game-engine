<?php 
	/*
		** @comment Для различных классов, которые не будут уместны в /classes
		** @comment Комментировать необязательно
		** @comment Пример использования: абстрактные классы, шаблоны
	*/
	
	class RaptorScratch {
	
		protected $vars;
		
		function __construct() 
		{
			$this->vars = array();
		}
		
		function get($key) 
		{
			return $this->__get($key);
		}
		
		function set($key, $val) 
		{
			return $this->__set($key, $value);
		}
		
		function __set($key, $val) 
		{
			$this->vars[$key] = $val;
		}
		
		function __get($key) 
		{
			return $this->vars[$key];
		}
		
		function __call($func, $args)
		{
			return;
		}
	}
	
?>