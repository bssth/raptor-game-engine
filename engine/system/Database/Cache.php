<?php
	/**
	 * Class provides multiple cache systems
	 */
	 
	namespace Database;
	
	class Cache
	{
		protected static $i = null;
	
		public static function prepare()
		{
			if(self::$i === null) {
				if(class_exists('\\Memcache'))
				{
					self::$i = new \Memcache;
					self::$i->connect(\Raptor\Config::cache_host, \Raptor\Config::cache_port);
				}
				else
				{
					self::$i = new \Database\Helpers\Nocache;
				}
			}
			return 1;
		}
	
		public static function __callStatic($f, $a)
		{
			self::prepare();
			return call_user_func_array(array(self::$i, $f), $a);
		}
	
	}