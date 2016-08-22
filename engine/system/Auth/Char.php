<?php
	/**
	 * Character is the main game object that belongs to any player
	 */
	 
	namespace Auth;
	
	class Char
	{
		public $id = null;
		public $param = null;
		public $client = null;
		public $location = null;
		public $money = null;
		
		public function __construct($id)
		{
			$this->id = (string)$id;
			$this->precache();
			$this->param = new \Mmorpg\Parameter('char', (string)$id);
			$this->client = new \Mmorpg\Client((string)$id);
			$this->money = new \Mmorpg\Wallet((string)$id);
			$this->checkTimers();
			
			if($this->__get('location') != 0)
				$this->location = new \Mmorpg\Location($this->__get('location'));
			else
				$this->location = (object)['id' => '0', 'name' => 'неизвестная локация'];
		}
		
		public function __get($k)
		{
			$arr = \Database\Cache::get('char_' . $this->id);
			return (isset($arr[$k])) ? $arr[$k] : null;
		}
		
		public function checkPermission($perm)
		{
			return (in_array('admin.all', $this->perms) or in_array((string)$perm, $this->perms));
		}
		
		public function setPermission($perm, $status = true)
		{
			if(!is_bool($status)) {
				return false;
			}
			
			$perms = is_array($this->perms) ? $this->perms : array();
			if($status === true) {
				$perms[] = $perm;
			}
			else {
				unset($perms[array_search($perm, $perms)]);
			}
			$this->perms = array_values($perms);
			\Raptor\EventListener::invoke('set_perms', $perm, $status); 
			return true; 
		}
		
		public function setLocation($id)
		{
			$this->__set('location', $id);
			\Raptor\EventListener::invoke('char_teleported', $char->id, $id); 
			
			if($id != 0) {
				$this->location = new \Mmorpg\Location($id);
				return true;
			}
			else {
				$this->location = (object)['id' => '0', 'name' => 'неизвестная локация'];
				return false;
			}
		}
		
		public function __set($k, $v)
		{
			$arr = \Database\Cache::get('char_' . $this->id);
			$arr[$k] = $v;
			\Database\Cache::set('char_' . $this->id, $arr, null, 3600);
			\Database\Current::update('characters', array('_id' => $this->id), $arr);
			\Raptor\EventListener::invoke('changed_char', $k, $v); 
		}
		
		public function isOnline()
		{
			return ($this->online > time());
		}
		
		public function __toString()
		{
			return (string)$this->id;
		}
		
		public function setOnline()
		{
			if($this->isOnline()) {
				return false;
			}
			
			$newval = (string)(time() + 300);
			$this->online = $newval;
			$lst = \Auth\Char::onlineList();
			$lst[$this->id] = $newval;
			\Database\Cache::set('online_list', $lst, null, 300);
			\Raptor\EventListener::invoke('on_online', $this->id); 
			return true;
		}

		public static function getActions()
		{
			$test = \Database\Cache::get('char_act');
			if(is_array($test)) {
				return $test;
			}
			
			$data = \Database\Current::getAll('char_act', array());
			\Database\Cache::set('char_act', $data, null, 3600);
			return $data;
		}
		
		public function checkTimers()
		{
			$list = \Database\Cache::get('timers_char_' . $this->id);
			if(!is_array($list)) $list = [];
			foreach($this->getPremium() as $k => $v) {
				$list['cexpired_'.$k] = [
					'char' => $v['char'],
					'id' => 'cexpired_' . $v['item'],
					'vars' => [],
					'time' => $v['expires']
				];
				\Database\Cache::delete('charbought_' . $this->id);
				\Database\Current::remove('char_bought', array('_id' => $v['_id']));
			}

			if(!is_array($list)) return false;
			
			foreach($list as $k => $v) {
				if(time() > $v['time'])
					\Raptor\EventListener::invoke('timeout', $v['char'], $v['id'], $v['vars']);
			}
			return true;
		}
		
		public function getPremium()
		{
			$test = \Database\Cache::get('charbought_' . $this->id);
			if(is_array($test) and isset($test['_id'])) {
				return is_array($test) ? $test : [];
			}
			
			$data = \Database\Current::getAll('char_bought', array('char' => $this->id));
			\Database\Cache::set('charbought_' . $this->id, $data, null, 3600);
			
			return is_array($data) ? $data : [];
		}
		
		public function setPremium($id, $timeout)
		{
			\Database\Current::insert('char_bought', [
				'char' => $this->id,
				'item' => $id,
				'expires' => time()+$timeout
			]);
			\Database\Cache::delete('charbought_' . $this->id);
			
			return true;
		}
		
		public function setTimer($id = 'noname', $time = 0, $vars = [])
		{
			$list = \Database\Cache::get('timers_char_' . $this->id);
			if(!is_array($list)) 
				$list = [];
			
			$time = time()+$time;
			$list[$id] = ['char' => $this->id, 'time' => $time, 'id' => $id, 'vars' => $vars];
			
			\Database\Cache::set('timers_char_' . $this->id, $list, null, 3600);
			return true;
		}
		
		public static function onlineList($as_obj = false)
		{
			$test = \Database\Cache::get('online_list');
			if(is_array($test)) {
				if($as_obj == false)
				{
					return $test;
				}
				else
				{
					$return = array();
					foreach($test as $k => $v)
					{
						try {
							$return[] = new \Auth\Char($k);
						}
						catch(\Raptor\Exception $e) {
							continue;
						}
					}
					return $return;
				}
			}
			else
			{
				return array();
			}
		}
		
		public static function create($name = null, $player = null)
		{
			if(!is_string($name) or !is_string($player))
			{
				throw new \Raptor\Exception('Bad input data');
			}
			
			$test = \Database\Current::getOne('characters', array('name' => $name));
			if(is_array($test) and isset($test['name']))
			{
				return false;
			}
			
			if(strlen($name) > 20 or strlen($name) < 2)
			{
				return false;
			}
			
			\Database\Current::insert('characters', array(
				'name' => $name,
				'player' => $player,
				'info' => array(),
				'inventory' => array(),
				'money' => array(),
				'ban' => '0',
				'location' => '0',
				'pos_x' => '0',
				'pos_y' => '0',
				'pos_z' => '0',
				'vworld' => '0',
				'perms' => array()
			));
			
			return true;
		}
		
		public static function select($query)
		{
			$result = \Database\Current::getOne('characters', $query);
			
			return (is_array($result)) ? (new Char($result['_id'])) : null;
		}
		
		public function delete()
		{
			\Database\Cache::delete('char_' . $this->id);
			return \Database\Current::remove('characters', array('_id' => $this->id));
		}
		
		public function precache()
		{
			$test = \Database\Cache::get('char_' . $this->id);
			if(is_array($test) and isset($test['_id'])) {
				return 0;
			}
			
			$data = \Database\Current::getOne('characters', array('_id' => $this->id));
			
			if(!isset($data['_id'])) {
				throw new \Raptor\Exception('Cannot precache character - doesnt exist');
			}
			
			\Database\Cache::set('char_' . $this->id, $data, null, 3600);
			
			return 1;
		}
		
	}