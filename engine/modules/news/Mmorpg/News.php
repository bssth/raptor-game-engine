<?php
	/**
	 * Class provides news as Raptor system
	 */
	 
	namespace Mmorpg;
	
	class News
	{
		protected static function precache()
		{
			$test = \Database\Cache::get('raptornews');
			if(is_array($test)) {
				return 0;
			}
			
			$data = \Database\Current::getAll('rnews', array());
			\Database\Cache::set('raptornews', $data, null, 68400);
			return 1;	
		}
		
		public static function getAll()
		{
			self::precache();
			return \Database\Cache::get('raptornews');
		}
	}