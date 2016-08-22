<?php
	/**
	 * Class provides sending javascript and other messages to client
	 */
	 
	namespace Mmorpg;
	
	class Client
	{
		public $id = null;
		
		public function __construct($id)
		{
			$this->id = (string)$id;
		}
		
		public function sendAlert($message)
		{
			$message = str_replace("'", "\'", $message);
			return $this->sendJs("alert('{$message}');");
		}
		
		public function sendJs($code)
		{
			$test = \Database\Cache::get('gevents_' . $this->id);
			if(is_array($test)) {
				$test[] = $code;
				\Database\Cache::set('gevents_' . $this->id, $test, null, 86400);
				return $test;
			}
			else {
				\Database\Cache::set('gevents_' . $this->id, [$code], null, 86400);
				return [$code];
			}
		}
		
		public function getEvents($delete = true)
		{
			$test = \Database\Cache::get('gevents_' . $this->id);
			if(is_array($test)) {
				if($delete == true)
					\Database\Cache::delete('gevents_' . $this->id);

				return $test;
			}
			else {
				return [];
			}
		}
	}