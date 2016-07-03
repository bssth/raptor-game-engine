<?php
	/**
	 * Character is the main game object that belongs to any player
	 */
	 
	namespace Auth;
	
	class Char
	{
		public $id = null;
		public $param = null;
		
		public function __construct($id)
		{
			$this->id = (string)$id;
			$this->param = new \Mmorpg\Parameter('char', (string)$id);
			$this->precache();
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
			if(!is_bool($status))
			{
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