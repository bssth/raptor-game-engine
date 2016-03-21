<?php
	/**
	 * API class provides authentification 
	 */
	
	namespace Api;
	use \Database\DBM as DB;
	use \Database\Cache as Cache;
	
	class Auth
	{
		
		/**
		 * Log-in and get token 
		 */
		public function login()
		{
			if(!isset($_REQUEST['login']) or !isset($_REQUEST['password'])) {
				return array('code' => '050', 'error' => 'Login or password is not passed');
			}
			
			$data = \Player\Player::login($_REQUEST['login'], $_REQUEST['password']);
			
			if($data == false) {
				return array('code' => '051', 'error' => 'Invalid login or password');
			}
			
			return array(
				'id' => (string)$data['_id'],
				'token' => (string)$data['token'],
				'access' => (array)$data['access'],
				'characters' => (array)$data['characters']
			);
		}
		
		/**
		 * Get all characters
		 */
		public function chars()
		{
			if(!isset($_REQUEST['player']) or !isset($_REQUEST['token'])) {
				return array('code' => '050', 'error' => 'Name, token or player ID is not passed');
			}
			
			if(\Player\Player::check_token($_REQUEST['player'], $_REQUEST['token']) == false) {
				return array('code' => '051', 'error' => 'Invalid token passed');
			}
			
			return (array) (new \Player\Player($_REQUEST['player']))->getChars();
		}
		
		/**
		 * Gots all online characters 
		 */
		public function online()
		{
			return \Character\Char::getOnlines();
		}
		
		/**
		 * Create new character 
		 */
		public function makechar()
		{
			if(!isset($_REQUEST['name']) or !isset($_REQUEST['player']) or !isset($_REQUEST['token'])) {
				return array('code' => '050', 'error' => 'Name, token or player ID is not passed');
			}
			
			if(\Player\Player::check_token($_REQUEST['player'], $_REQUEST['token']) == false) {
				return array('code' => '051', 'error' => 'Invalid token passed');
			}
			
			$ans = \Character\Char::create($_REQUEST['name'], $_REQUEST['player']);
			
			return ($ans === true) ? array('answer' => true) : $ans;
		}
		
		public function setonline()
		{
			if(!isset($_REQUEST['id']) or !isset($_REQUEST['token']) or !isset($_REQUEST['player'])) {
				return array('code' => '051', 'error' => 'You must pass character ID, Player ID and Token');
			}
			if(!is_bool(\Character\Char::exists($_REQUEST['id'])) or \Character\Char::exists($_REQUEST['id']) === false) {
				return array('code' => '051', 'error' => 'Character doesn\'t exists');
			}
			if(\Player\Player::check_token($_REQUEST['player'], $_REQUEST['token']) == false) {
				return array('code' => '050', 'error' => 'Invalid player token');
			}
			
			(new \Character\Char($_REQUEST['id']))->setOnline();
			
			return array('answer' => true);
		}
		
		/**
		 * Get character data
		 */
		public function data()
		{
			if(!isset($_REQUEST['id'])) {
				return array('code' => '051', 'error' => 'You must pass character ID');
			}
			
			if(!is_bool(\Character\Char::exists($_REQUEST['id'])) or \Character\Char::exists($_REQUEST['id']) === false) {
				return array('code' => '051', 'error' => 'Character doesn\'t exists');
			}
			
			$data = (new \Character\Char($_REQUEST['id']))->data();
			(new \Character\Char($_REQUEST['id']))->setOnline();
			
			$answer = array(
				'id' => (string)$data['_id'],
				'name' => isset($data['name']) ? $data['name'] : 'none'
			);
			
			if(isset($_REQUEST['token']) and \Player\Player::check_token($data['player'], $_REQUEST['token'])) {
				$answer = array_merge($answer, array(
						'player' => isset($data['player']) ? $data['player'] : false,
						'parameters' => isset($data['params']) ? $data['params'] : array(),
						'currency' => isset($data['money']) ? $data['money'] : array(),
						'form' => isset($data['form']) ? $data['form'] : array()
					)
				);
			}
			
			return $answer;
		}
		
		/**
		 * Register and get token
		 */
		public function register()
		{
			return \Player\Player::register($_REQUEST['email'], $_REQUEST['login'], $_REQUEST['password'], $_SERVER['REMOTE_ADDR']);
		}
		 
		
	}