<?php
	/**
	 * Logging engine. Saves logs in database and then they can be got from it 
	 */
	 
	namespace History;
	
	class Logger
	{
		
		/**
		 * Gets all logs
		 * @return array
		 */
		public static function getAll()
		{
			$test = \Database\Cache::get('all_logs');
			if(is_array($test)) {
				return $test;
			}
			
			$data = \Database\Current::getAll('logs', array());
			\Database\Cache::set('all_logs', $data, null, 3600);
			return array_reverse($data);
		}
		
		/**
		 * Get logs by query
		 * @return array
		 */
		public static function get($needle)
		{
			return array_reverse(\Database\Current::getAll('logs', $needle));
		}
		
		/**
		 * Write log
		 * @param string $char
		 * @param string $desc
		 * @param string $cat
		 * @param string $icon
		 * @return boolean
		 */
		public static function add($char, $desc, $cat, $icon = 'legal')
		{
			\Database\Current::insert('logs', array(
				'char' => (string)$char,
				'desc' => (string)$desc,
				'cat' => (string)$cat,
				'time' => (string)time(),
				'icon' => (string)$icon
			));
			\Database\Cache::delete('logs_' . $cat);
			return true;
		}
		
		/**
		 * Get all logs in category
		 * @param string $cat
		 * @return array 
		 */
		public static function getCat($cat)
		{
			$test = \Database\Cache::get('logs_' . $cat);
			if(is_array($test)) {
				return array_reverse($test);
			}
			
			$data = \Database\Current::getAll('logs', array('cat' => $cat));
			\Database\Cache::set('logs_' . $cat, $data, null, 3600);
			return array_reverse($data);
		}
		
	}