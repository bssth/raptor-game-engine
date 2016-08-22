<?php
	/**
	 * Class provides bridge between currency engine and character
	 */
	
	namespace Mmorpg;
	
	class Inventory
	{
		protected $id = null;
		protected $inv = null;
		protected $gi_cache = null;
		
		public function __construct($id)
		{
			$this->id = $id;
			
			$test = \Database\Cache::get('char_' . $this->id);
			if(!is_array($test) or !isset($test['inventory'])) {
				$test['inventory'] = "a:0:{}";
			}
			
			try {
				$temp = unserialize($test['inventory']);
				foreach($temp as $k => $v)
					$this->inv[$v['slot']] = $v;
			}
			catch(\Raptor\Exception $e) {
				$this->inv = [];
			}
		}
		
		public static function getItems()
		{
			$test = \Database\Cache::get('inv_items');
			if(is_array($test)) {
				return $test;
			}
			
			$data = \Database\Current::getAll('inv_items', array());
			if(!is_array($data)) {
				$data = [];
			}
			
			\Database\Cache::set('inv_items', $data, null, 3600);
			return $data;
		}
		
		public static function getItem($id)
		{
			$test = \Database\Cache::get('inv_item_' . $id);
			if(is_array($test)) {
				return $test;
			}
			
			$items = self::getItems();
			$data = [];
			foreach($items as $k => $v)
				if($v['_id'] == $id)
					$data = $v;
			
			\Database\Cache::set('inv_item_' . $id, $data, null, 3600);
			return $data;
		}
		
		public function giveItem($id, $vars)
		{
			$slot = uniqid();
			$this->inv[uniqid()] = ['id' => $id, 'vars' => $vars, 'slot' => $slot];
			$this->gi_cache = null;
			return $slot;
		}
		
		public function removeItem($slot)
		{
			unset($this->inv[$slot]);
			$this->gi_cache = null;
			return isset($this->inv[$slot]) ? false : true;
		}
		
		public function setSlotVar($slot, $var, $value)
		{
			$this->gi_cache = null;
			return ($this->inv[$slot]['vars'][$var] = $value);
		}
		
		public function getSlot($slot)
		{
			return isset($this->getInventory()[$slot]) ? $this->getInventory()[$slot] : null;
		}
		
		public function getSlotVar($slot, $var)
		{
			if(isset($this->getInventory()[$slot][$var]))
				return $this->getInventory()[$slot][$var];
			elseif($this->getInventory()[$slot]['vars'][$var])
				return $this->getInventory()[$slot]['vars'][$var];
			else
				return null;
		}
		
		public function getInventory()
		{ 
			if(is_array($this->gi_cache)) return $this->gi_cache;
			if(!is_array($this->inv)) return [];
			
			$return = [];
			foreach($this->inv as $k => $v)
				$return[$k] = array_merge(\Mmorpg\Inventory::getItem($v['id']), $v, $v['vars'], ['slot' => $k]);

			return $return;
		}
		
		public function __destruct()
		{
			$this->updateDatabase();
		}
		
		protected function updateDatabase()
		{
			$arr = \Database\Cache::get('char_' . $this->id);
			$arr['inventory'] = serialize($this->inv);
			\Database\Cache::set('char_' . $this->id, $arr, null, 3600);
			\Database\Current::update('characters', array('_id' => $this->id), $arr);
			\Raptor\EventListener::invoke('changed_char', $this->id, 'inventory', $arr['inventory']);
			return true;
		}
	}