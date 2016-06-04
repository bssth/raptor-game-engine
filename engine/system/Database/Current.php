<?php
	/**
	 * Main class provides working with current database
	 * @author Mike Chip
	 */
	 
	namespace Database;
	
	class Current
	{
		protected static $type = null;
		protected static $i = null;
		
		public static function prepare()
		{
			if(self::$type !== null)
			{
				return 0;
			}
			
			self::$type = \Raptor\Config::db_type;
		}
		
		public static function __callStatic($f, $a)
		{
			if(self::$i === null) {
				self::prepare();
				$class = '\\Database\\Drivers\\' . self::$type;
				self::$i = new $class;
			}
			
			return call_user_func_array(array(self::$i, $f), $a);
		}
		
		public static function info()
		{
			self::prepare();
			return 'Database type is ' . self::$type;
		}
	}