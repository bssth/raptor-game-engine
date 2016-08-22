<?php
	
	namespace Mmorpg;
	
	class Texts
	{
		protected static $storage = null;
		
		/**
		 * Prepare text engine
		 */
		protected static function prepare()
		{
			$test = \Database\Cache::get('text_fields');
			if(is_array($test) and count($test))
				return $test;

			
			$data = \Database\Current::getAll('text_fields', array());
			\Database\Cache::set('text_fields', $data, null, 3600);
			return $data;
		}
		
		/**
		 * Get value by variable name
		 * @param string $var
		 * @return string
		 */
		public static function get($var)
		{
			return isset(self::$storage[$var]) ? self::$storage[$var] : $var;
		}
	}