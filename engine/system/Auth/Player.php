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
			\Database\Current::update('players', array('_id' => $this->id), $arr);
		}
		
		/**
		 * Register player
		 * @param string $login
		 * @param string $password
		 * @return boolean
		 */
		public static function register($login = null, $password = null)
		{
			if(!is_string($login) or !is_string($password))
			{
				throw new \Raptor\Exception('Bad input data');
			}
			
			$test = \Database\Current::getOne('players', array('login' => $login));
			if(is_array($test) and isset($test['login']))
			{
				return false;
			}
			
			if(strlen($login) > 20 or strlen($login) < 2 or strlen($password) < 6)
			{
				return false;
			}
			
			\Database\Current::insert('players', array(
				'login' => $login,
				'password' => sha1($password),
				'last_ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'error',
				'last_date' => time(),
				'reg_ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'error',
				'reg_date' => time(),
				'domain' => isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'error'
			));
			
			\Raptor\EventListener::invoke('registered', $login); 
			return true;
		}
		
		/**
		 * Select player by query
		 * @param string $query
		 * @return array
		 */
		public static function select($query)
		{
			$result = \Database\Current::getOne('players', $query);
			
			return (is_array($result)) ? (new Player($result['_id'])) : null;
		}
		
		/**
		 * Get all characters of player as objects
		 * @return array
		 */
		public function getCharacters()
		{
			$list = \Database\Current::getAll('characters', array('player' => $this->id));
			$res = array();
			foreach($list as $l)
			{
				if(!isset($l['name'])) continue;
				$res[] = new \Auth\Char((string)$l['_id']);
			}
			return $res;
		}
		
		/**
		 * Add player data to cache 
		 * @return boolean
		 */
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