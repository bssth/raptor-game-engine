<?php

	class Modules 
	{
		
		public $list;
		protected $saveOnDestruct;
		static $instance = null;
		
		public static function i()
		{
			if(!is_object(self::$instance))
			{
				self::$instance = new self;
				return self::$instance;
			}
			else
			{
				return self::$instance;
			}
		}

		function __construct($list = null)
		{
			if(is_array($list))
			{
				$this->list = array_values($list);
				$this->saveOnDestruct = false;
			}
			else
			{
				$mods = Raptor::Config();
				$this->list = is_array(@$mods['modules']) ? @$mods['modules'] : array();
				$this->saveOnDestruct = false;
			}
			
		}

		function getModules()
		{
			return $this->list;
		}

		function enable($mod)
		{
			if (!in_array($mod, $this->list)) 
			{
				$this->list[] = $mod;
				$this->saveOnDestruct = true;
			}
		}

		function disable($mod)
		{
			$key = array_search($mod, $this->list);
			if ($key !== false) 
			{
				unset($this->list[$key]);
				 $this->saveOnDestruct = true;
			}
		}

		function save()
		{
			$array = array("modules" => array_values($this->list));
			Database::Edit("config", array("active" => '1'), $array);
		}

		function __destruct()
		{
			if ($this->saveOnDestruct == true) 
			{
				$this->save();
			}
		}
		
		function InitModules($modules)
		{
			if (is_array($modules) and count($modules) >= 1) 
			{
				$return = true;
			}
			else
			{
				$return = false;
				$modules = $this->getModules();
			}
			
			foreach ($modules as $module) 
			{
				if (!file_exists(MODS_ROOT . SEPARATOR . $module . SEPARATOR . "global.php")) 
				{
					continue;
				}
				@include_once(MODS_ROOT . SEPARATOR . $module . SEPARATOR . "global.php");
			}
			
			return $return;
			
		}

	}
