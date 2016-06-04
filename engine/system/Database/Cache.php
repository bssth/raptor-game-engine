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
				self::$i = new \Memcache;
				self::$i->connect(\Raptor\Config::cache_host, \Raptor\Config::cache_port);
			}
			return 1;
		}
	
		public static function __callStatic($f, $a)
		{
			self::prepare();
			return call_user_func_array(array(self::$i, $f), $a);
		}
	
	}