<?php
	/**
	 * Player control class 
	 */
	 
	namespace Player;
	
	use \Database\DBM as DB;
	use \Database\Cache as Cache;
	
	class Player
	{
	
		/**
		 * Player identifier
		 */
		public $id = null;
		
		/**
		 * Player data 
		 */
		protected $data = null;
		
		/** 
		 * Makes instance
		 * @param string $id
		 */
		public function __construct($id)
		{
			if(!isset($id)) {
				throw new \Fructum\Exception('ID is not passed');
			}
			
			$this->id = $id;
			
			$this->precache(); // pre-cache player data
		}
		
		/**
		 * Gets all rows from players data 
		 * @return array
		 */
		public function data()
		{
			return !is_null($this->data) ? $this->data : array();
		}
		
		/**
		 * Gets row from players data 
		 * @param string $k
		 * @return mixed
		 */
		public function __get($k)
		{
			return isset($this->data[$k]) ? $this->data[$k] : null;
		}
		
		/**
		 * Checks if token is valid 
		 * @param string $id 
		 * @param string $token
		 * @return boolean 
		 */
		public static function check_token($id, $token)
		{
			if(!self::exists($id)) {
				return false;
			}
			
			$pl = new \Player\Player($id);
			
			if($pl->token != $token) {
				return false;
			}
			
			return true;
		}
		
		/**
		 * Checks if player exists
		 * @param string $id
		 * @return boolean
		 */
		public static function exists($id)
		{
			if(!is_scalar($id)) {
				return false;
			}
			
			$cache = Cache::get('player_' . $id);
			
			if(is_array($cache) and isset($cache['_id'])) {
				return true;
			}
			
			$data = DB::i()->players->findOne( array('_id' => DB::i()->asId($id)) );
			
			if(!isset($data['_id'])) {
				return false;
			}
			
			return true;
		}
		
		/**
		 * Exchanges login and password to data about character
		 * @param string $login 
		 * @param string $password 
		 * @return array
		 */
		public static function login($login, $password)
		{
			$data = DB::i()->players->findOne(array('login' => $login, 'password' => md5($password)));
			
			if(!isset($data['login'])) {
				return false;
			}
			
			$chars = array();
			$query = DB::i()->characters->find( array('player' => (string)$data['_id']) );
			foreach($query as $a) {
				$chars[] = array('name' => (string)$a['name'], 'id' => (string)$a['_id']);
			}
			
			$data['characters'] = $chars;
			
			return $data;
		}
		
		/**
		 * Get all player's characters 
		 */
		public function getChars()
		{
			$cache = Cache::get('plchars_' . $this->id);
			
			if(is_array($cache)) {
				return $cache;
			}
		
			$chars = array();
			$query = DB::i()->characters->find( array('player' => (string)$this->id) );
			foreach($query as $a) {
				$chars[] = array('name' => (string)$a['name'], 'id' => (string)$a['_id']);
			}
			
			Cache::set('plchars_' . $this->id, $chars, null, 7200);
			
			return $chars;
		}
		
		/**
		 * Pre-cache player's data 
		 * @return boolean
		 */
		public function precache()
		{
			if(!is_scalar($this->id)) {
				return false;
			}
			
			$cache = Cache::get('player_' . $this->id);
			
			if(is_array($cache)) {
				$this->data = $cache;
				return true;
			}
			
			$data = DB::i()->players->findOne( array('_id' => DB::i()->asId($this->id)) );
			
			if(!isset($data['_id'])) {
				throw new \Fructum\Exception('Player doesnt exists. Use ' . __CLASS__ . '::exists to check it.');
			}
			
			$this->data = $data;
			
			Cache::set('player_' . $this->id, $data, null, 7200);
			
			return true;
		}
		
		/**
		 * Register new player
		 * @param string $email 
		 * @param string $login 
		 * @param string $password 
		 * @param string $ip 
		 * @return array
		 */
		public static function register($email, $login, $password, $ip = null)
		{
			if(!isset($email) or !isset($login) or !isset($password)) { return array('code' => '051', 'error' => 'You must pass login, password and email'); }
			if(strlen($login) > 25 or strlen($login) < 4) { return array('code' => '051', 'error' => 'Login must have from 4 to 25 symbols'); }
			if(strlen($password) > 40 or strlen($password) < 6) { return array('code' => '051', 'error' => 'Password must have from 6 to 40 symbols'); }
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { return array('error' => 'Invalid email', 'code' => '051'); }
			if(is_null($ip)) { $ip = $_SERVER['REMOTE_ADDR']; }
			
			$data = DB::i()->players->findOne( array('login' => $login) );
			
			if(isset($data['login'])) {
				return array('error' => 'Account already exists', 'code' => '052');
			}
			
			$token = sha1($login . $password);
			
			$data = array (
				'login' => $login,
				'password' => md5($password),
				'token' => $token,
				'reg_date' => time(),
				'reg_ip' => isset($ip) ? $ip : '0.0.0.0',
				'last_date' => time(),
				'last_ip' => isset($ip) ? $ip : '0.0.0.0',
				'domain' => isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '0.0.0.0',
				'access' => array()
			);
			
			DB::i()->players->insert($data);
			
			$data = DB::i()->players->findOne( array('token' => $token) );
			
			if(!isset($data['login'])) {
				return array('code' => 'Database Error', 'error' => '500');
			}
			
			return array(
				'token' => (string)$data['token'],
				'id' => (string)$data['_id']
			);
		}
	}