<?php
	/**
	 *	Native Raptor templater. Uses PHP variables in HTML templates
	 *
	 * @version 1.0 
	 * @author Mike Chip
	 */
	 
	namespace Raptor;
	
	class Templater
	{
		public $path = '';
		protected $vars = array();
	
		/**
		 * Creating instance. Template name is needed
		 * 
		 * @param string $tpl
		 */
		public function __construct($tpl = 'empty')
		{
			if(file_exists(Config::ROOT . Core::SEPARATOR . 'templates' . Core::SEPARATOR . $tpl . '.html')) {
				$this->path = Config::ROOT . Core::SEPARATOR . 'templates' . Core::SEPARATOR . $tpl . '.html';
			}
			else if(file_exists(Config::ROOT . Core::SEPARATOR . 'templates' . Core::SEPARATOR . 'default' . Core::SEPARATOR . $tpl . '.html')) {
				$this->path = Config::ROOT . Core::SEPARATOR . 'templates' . Core::SEPARATOR . 'default' . Core::SEPARATOR . $tpl . '.html';
			}
			else {
				throw new \Raptor\Exception('Template is not found');
			}
		}
		
		public static function exists($tpl = 'empty')
		{
			if(file_exists($tpl) or file_exists(Config::ROOT . Core::SEPARATOR . 'templates' . Core::SEPARATOR . $tpl . '.html'))
			{
				return true;
			}
			return false;
		}
		
		/**
		 * Sets value of variable 
		 * 
		 * @param string $key 
		 * @param mixed $value
		 * @return void
		 */
		public function __set($key, $value)
		{
			$this->vars[$key] = $value;
		}
		
		/**
		 * Gets templates root
		 */
		public static function root()
		{
			return Config::ROOT . Core::SEPARATOR . 'templates';
		}
		
		/**
		 * Gets value of variable 
		 *
		 * @param string $key
		 * @return mixed
		 */
		public function __get($key)
		{
			return ( isset($this->vars[$key]) ? $this->vars[$key] : null );
		}
		
		/**
		 * @see \Templater\Native::__set
		 * @return object
		 */
		public function set($key, $value)
		{
			$this->__set($key, $value);
			return $this;
		}
		
		/**
		 * Just get template source 
		 * @return string
		 */
		public function source()
		{
			if(!file_exists($this->path)) {
				throw new Exception("Template not found in {$this->path}");
			}
			return file_get_contents($this->path);
		}
		
		/**
		 * Save template 
		 * @return boolean
		 */
		public static function save($tpl, $html)
		{
			file_put_contents(Config::ROOT . Core::SEPARATOR . 'templates' . Core::SEPARATOR . $tpl . '.html', $html);
			return true;
		}
		
		/**
		 * Evaluates code and returns result
		 *
		 * @return string
		 */
		public function render()
		{
			if(!file_exists($this->path)) {
				throw new Exception("Template not found in {$this->path}");
			}
			foreach($this->vars as $k => $v) {
				$$k = $v; // set all variables locally, because of require_once function
			}
			ob_start(NULL, 0, PHP_OUTPUT_HANDLER_CLEANABLE | PHP_OUTPUT_HANDLER_FLUSHABLE | PHP_OUTPUT_HANDLER_REMOVABLE); // start output buffer temporary
			require_once($this->path); // evaluate template with PHP code 
			return ob_get_clean(); // clear output buffer with template and return it
		}
	}