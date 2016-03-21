<?php
	/*
	 * Configuration API
	 */
	 
	namespace Api;
	
	class Game
	{
		
		public function info()
		{
			return array(
				'title' => \Game\Config::game_title,
				'meta_desc' => \Game\Config::meta_desc,
			);
		}
		
	}