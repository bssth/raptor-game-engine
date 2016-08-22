<?php
	/**
	 * Class provides bridge between currency engine and character
	 */
	
	namespace Mmorpg;
	
	class Wallet
	{
		protected $id = null;
		protected $money = null;
		
		public function __construct($id)
		{
			$this->id = $id;
			
			$test = \Database\Cache::get('char_' . $this->id);
			if(!is_array($test) or !isset($test['money'])) {
				throw new \Raptor\Exception('Character is not precached or undefined');
			}
			
			try {
				$this->money = unserialize($test['money']);
			}
			catch(\Raptor\Exception $e) {
				$this->money = [];
			}
		}
		
		protected function updateDatabase()
		{
			$arr = \Database\Cache::get('char_' . $this->id);
			$arr['money'] = serialize($this->money);
			\Database\Cache::set('char_' . $this->id, $arr, null, 3600);
			\Database\Current::update('characters', array('_id' => $this->id), $arr);
			\Raptor\EventListener::invoke('changed_char', $this->id, 'money', $arr['money']);
			\Raptor\EventListener::invoke('money_change', $this->id, $this->money);
			return true;
		}
		
		public function giveMoney($currency, $count)
		{
			if(!isset($this->money[$currency]) or !is_numeric($count))
				return ($this->money[$currency] = $count);
			
			$this->money[$currency] += $count;
			if($this->money[$currency] < 0) {
				$this->money[$currency] = 0;
			}
			
			\Raptor\EventListener::invoke('give_money', $this->id, $currency, $count);
			return ($this->money[$currency]);
		}
		
		public function __set($k, $v) {
			$this->money[$k] = $v;
		}
		
		public static function getCurrencies()
		{
			$test = \Database\Cache::get('currency');
			if(is_array($test)) {
				return $test;
			}
			
			$data = \Database\Current::getAll('currency', array());
			if(!is_array($data))
				$data = [];
			
			\Database\Cache::set('currency', $data, null, 3600);
			return $data;
		}
		
		public function __destruct()
		{
			$this->updateDatabase();
		}
		
		public function __get($k) {
			return isset($this->money[$k]) ? $this->money[$k] : 0;
		}

		public function __toString() {
			return (string)$this->id;
		}
	}