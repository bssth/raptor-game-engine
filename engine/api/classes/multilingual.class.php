<?php
	/*
		** @comment Класс, позволяющий создать мультиязычность в вашей игре
		** @last_edit 24.08.2015
		** @last_autor Mike
	*/
	
	class Multilingual
	{
		protected $words;
		var $language;
		
		function __construct($lang = null)
		{
			if(!is_null($lang))
			{
				$this->load($lang);
			}
		}
		
		function load($language)
		{
			$this->language = $language;
			$this->words = parse_ini_file(LANG_ROOT . SEPARATOR . $language . ".ini");
		}
		
		function exists($lang = null)
		{
			if($lang === null) { $lang = $this->language; }
			return file_exists(LANG_ROOT . SEPARATOR . $lang . ".ini");
		}
		
		function reload()
		{
			$this->words = parse_ini_file(LANG_ROOT . SEPARATOR . $this->language . ".ini");
		}
		
		function __get($key)
		{
			return $this->words[$key];
		}
		
		function __set($key, $value)
		{
			$this->words[$key] = $value;
			return true;
		}
		
		function append($key, $value)
		{
			# reserved
			return null;
		}
	}
	