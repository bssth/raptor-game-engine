<?php
	/**
	 * Edition of templater that supports default templates
	 */
	
	namespace Raptor;
	use \Fructum\Core as Core;
	
	class Templater extends \Templater\Native
	{
		
		/**
		 * Creating instance. Template name is needed
		 * 
		 * @param string $tpl
		 */
		public function __construct($tpl = 'empty')
		{
			$this->path = file_exists(Core::root() . Core::SEPARATOR . 'templates' . Core::SEPARATOR . $tpl . '.html') ? Core::root() . Core::SEPARATOR . 'templates' . Core::SEPARATOR . $tpl . '.html' : (__DIR__ . Core::SEPARATOR . '..' . Core::SEPARATOR . 'deftpls' . Core::SEPARATOR . $tpl . '.html'); // if there is full path - write, else detect itself
			
			if(!file_exists($this->path)) {
				throw new \Fructum\Exception('Template is not found');
			}
		}
		
	}