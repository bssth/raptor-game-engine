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
		
		/**
		 * Get variable of location
		 * @param string $k
		 * @return mixed
		 */
		public function __get($k) {
			return isset($this->info[$k]) ? $this->info[$k] : null;
		}
		
		/**
		 * Get location as HTML (template: locationtype_TYPE)
		 * @return string
		 */
		public function asHTML() {
			return (new \Raptor\Templater('locationtype_' . $this->type))->set('location', $this)->render();
		}
		
		/**
		 * Get variable (a.k.a. parameter) of location
		 * @param string $var
		 * @return mixed
		 */
		public function getVar($var)
		{
			if($this->vars == null)
				$this->vars = json_decode($this->info['vars'], true);

			
			return isset($this->vars[$var]) ? $this->vars[$var] : null;
		}
		
		/**
		 * Get location by id
		 * @param string $id
		 * @return array
		 */
		public static function getLocation($id)
		{
			$test = \Database\Cache::get('locations_list');
			if(is_array($test) and isset($test[$id]))
				return $test[$id];
			
			$test = self::getLocations();
			if(is_array($test) and isset($test[$id]))
				return $test[$id];

			throw new \Raptor\Exception('Trying to get undefined location');
			return [];
		}
		
		/**
		 * Get all locations
		 * @return array
		 */
		public static function getLocations()
		{
			$test = \Database\Cache::get('locations_list');
			if(is_array($test) and count($test))
				return $test;

			
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