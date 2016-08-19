<?php
	/**
	 * Class provides bridge between script engine and location
	 */
	
	namespace Mmorpg;
	
	class Location
	{
		protected $id = null;
		protected $info = null;
		protected $vars = null;
		
		public function __construct($id)
		{
			$this->id = $id;
			$this->info = \Mmorpg\Location::getLocation($id);
		}
		
		public function __get($k) {
			return isset($this->info[$k]) ? $this->info[$k] : null;
		}
		
		public function asHTML() {
			return (new \Raptor\Templater('locationtype_' . $this->type))->set('location', $this)->render();
		}
		
		public function getVar($var)
		{
			if($this->vars == null) {
				$this->vars = json_decode($this->info['vars'], true);
			}
			
			return isset($this->vars[$var]) ? $this->vars[$var] : null;
		}
		
		public static function getLocation($id)
		{
			$test = \Database\Cache::get('locations_list');
			if(is_array($test) and isset($test[$id])) {
				return $test[$id];
			}
			
			$test = self::getLocations();
			if(is_array($test) and isset($test[$id])) {
				return $test[$id];
			}
			throw new \Raptor\Exception('Trying to get undefined location');
			return [];
		}
		
		public static function getLocations()
		{
			$test = \Database\Cache::get('locations_list');
			if(is_array($test) and count($test)) {
				return $test;
			}
			
			$data = \Database\Current::getAll('locations', array());
			$real = [];
			foreach($data as $k => $v)
			{
				$real[$v['_id']] = $v;
			}
			$data = $real;
			
			\Database\Cache::set('locations_list', $data, null, 3600);
			return $data;
		}

		public function __toString() {
			return (string)$this->id;
		}
	}