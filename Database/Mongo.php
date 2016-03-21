<?php
	/**
		This class provides bridge between MongoDB and Fructum
	*/
	
	namespace Database;
	
	use \Game\Config as Config;
	use \MongoClient as MongoClient;
	
	class Mongo
	{
		private static $_handler = null;
		
		public static function i()
		{
			if(self::$_handler == null) {
				if(!class_exists('MongoClient', false)) {
					throw new \Fructum\Exception('MongoClient class not found');
				}
				
				if(is_null(Config::mongo_host)) {
					self::$_handler = new MongoClient;
				}
				else {
					self::$_handler = new MongoClient("mongodb://" . Config::mongo_user . ":" . Config::mongo_password . "@" . Config::mongo_host);
				}
			}
			
			$db = (is_string(Config::mongo_db)) ? Config::mongo_db : 'raptor_temp';
			return self::$_handler->$db;
		}
		
	}