<?php
	/**
	 * Character class
	 */
	 
	namespace Character;
	
	use \Database\Cache as Cache;
	use \Database\DBM as DB;
	
	class Char extends \Fructum\Extendable
	{	
	
		/**
		 * Character`s ID
		 */
		public $id = null;
		
		/**
		 * Character`s data 
		 */
		protected $data = null;
		
		/**
		 * Creates instance
		 * @param string $id
		 */
		public function __construct($id)
		{
			if(!isset($id)) {
				throw new \Fructum\Exception('ID is not passed');
			}
			
			$this->id = $id;
			
			$this->precache();
		}
		
		/**
		 * Gets all rows from characters data 
		 * @return array
		 */
		public function data()
		{
			return !is_null($this->data) ? $this->data : array();
		}
		
		/**
		 * Creates new character
		 * @param string $name 
		 * @param string $player
		 */
		public static function create($name, $player)
		{
			if(strlen($name) > 15 or strlen($name) < 4) { return array('code' => '051', 'error' => 'Name must have from 4 to 15 symbols'); }
			
			if(\Player\Player::exists($_REQUEST['player']) == false) {
				return array('code' => '404', 'error' => 'Player does not exists');
			}
			
			$data = DB::i()->characters->findOne( array('name' => $name) );
			
			if(isset($data['_id'])) {
				return array('error' => 'Account already exists', 'code' => '052');
			}
			
			DB::i()->characters->insert( array(
				'name' => (string)$name,
				'player' => (string)$player,
				'params' => array(),
				'money' => array(),
				'form' => array(),
				'vars' => array()
			) );
			
			Cache::delete('plchars_' . $this->id);
			
			return true;
		}
		
		/**
		 * Gets users are online
		 * @return array
		 */
		public static function getOnlines()
		{
			$cache = Cache::get('rpt_online');
			if(is_array($cache)) {
				return $cache;
			}
			
			Cache::set('rpt_online', array(), null, 120);
			
			return array();
		}
		
		/**
		 * Sets user online 
		 * @return boolean
		 */
		public function setOnline()
		{
			$cache = Cache::get('rpt_online');
			if(!is_array($cache)) {
				Cache::set('rpt_online', array($this->name => time()+120), null, 120);
			}
			else {
				$cache[$this->name] = time()+120;
				Cache::set('rpt_online', $cache, null, 120);
			}
			
			return ( in_array($this->name, $cache) );
		}
		
		/**
		 * Get if user is online 
		 * @return boolean
		 */
		public function isOnline()
		{
			$cache = Cache::get('rpt_online');
			if(!is_array($cache)) {
				\Character\Char::getOnlines();
				return false;
			}
			
			return ( in_array($this->name, $cache) );
		}
		
		/**
		 * Gets row from characters data 
		 * @param string $k
		 * @return mixed
		 */
		public function __get($k)
		{
			return isset($this->data[$k]) ? $this->data[$k] : null;
		}
		
		/**
		 * Checks if character exists
		 * @param string $id
		 * @return boolean
		 */
		public static function exists($id)
		{
			if(!is_scalar($id)) {
				return false;
			}
			
			$cache = Cache::get('char_' . $id);
			
			if(is_array($cache) and isset($cache['_id'])) {
				return true;
			}
			
			$data = DB::i()->characters->findOne( array('_id' => DB::i()->asId($id)) );
			
			if(!isset($data['_id'])) {
				return false;
			}
			
			return true;
		}
		
		/**
		 * Pre-cache character's data 
		 * @return boolean
		 */
		public function precache()
		{
			if(!is_scalar($this->id)) {
				return false;
			}
			
			$cache = Cache::get('char_' . $this->id);
			
			if(is_array($cache)) {
				$this->data = $cache;
				return true;
			}
			
			$data = DB::i()->characters->findOne( array('_id' => DB::i()->asId($this->id)) );
			
			if(!isset($data['_id'])) {
				throw new \Fructum\Exception('Character doesnt exists. Use ' . __CLASS__ . '::exists to check it.');
			}
			
			$this->data = $data;
			
			Cache::set('char_' . $this->id, $data, null, 7200);
			
			return true;
		}
		
	}