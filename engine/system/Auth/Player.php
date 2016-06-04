<?php
	/**
	 * Player is game object that has login and password and owns unlimited count of characters
	 */
	 
	namespace Auth;
	
	class Player
	{
		public $id = null;
		
		public function __construct($id)
		{
			$this->id = $id;
			$this->precache();
		}
		
		public function __get($k)
		{
			$arr = \Database\Cache::get('player_' . $this->id);
			return (isset($arr[$k])) ? $arr[$k] : null;
		}
		
		public function __set($k, $v)
		{
			$arr = \Database\Cache::get('player_' . $this->id);
			$arr[$k] = $v;
			\Database\Cache::set('player_' . $this->id, $arr, null, 3600);
			\Database\Current::update('players', array('_id' => $this->id), array($k => $v));
		}
		
		public static function select($query)
		{
			$result = \Database\Current::getOne('players', $query);
			
			return (is_array($result)) ? (new Player($result['_id'])) : null;
		}
		
		public function precache()
		{
			$test = \Database\Cache::get('player_' . $this->id);
			if(is_array($test) and isset($test['_id'])) {
				return 0;
			}
			
			$data = \Database\Current::getOne('players', array('_id' => $this->id));
			
			if(!isset($data['_id'])) {
				throw new \Raptor\Exception('Cannot precache player - doesnt exists');
			}
			
			\Database\Cache::set('player_' . $this->id, $data, null, 3600);
			
			return 1;
		}
		
	}