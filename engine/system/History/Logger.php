<?php
	/**
	 * Logging engine. Saves logs in database and then they can be got from it 
	 */
	 
	namespace History;
	
	class Logger
	{
	
		public static function getAll()
		{
			$test = \Database\Cache::get('all_logs');
			if(is_array($test)) {
				return $test;
			}
			
			$data = \Database\Current::getAll('logs', array());
			\Database\Cache::set('all_logs', $data, null, 3600);
			return $data;
		}
		
		public static function get($needle)
		{
			return \Database\Current::getAll('logs', $needle);
		}
		
		public static function add($char, $desc, $cat, $icon = 'legal')
		{
			\Database\Current::insert('logs', array(
				'char' => (string)$char,
				'desc' => (string)$desc,
				'cat' => (string)$cat,
				'icon' => (string)$icon
			));
			\Database\Cache::delete('logs_' . $cat);
			return true;
		}
		
		public static function getCat($cat)
		{
			$test = \Database\Cache::get('logs_' . $cat);
			if(is_array($test)) {
				return $test;
			}
			
			$data = \Database\Current::getAll('logs', array('cat' => $cat));
			\Database\Cache::set('logs_' . $cat, $data, null, 3600);
			return $data;
		}
		
	}