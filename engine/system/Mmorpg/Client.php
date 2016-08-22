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
		
		/**
		 * Send alert() function to client
		 * @param string $message
		 * @return array
		 */
		public function sendAlert($message)
		{
			$message = str_replace("'", "\'", $message);
			return $this->sendJs("alert('{$message}');");
		}
		
		/**
		 * Send custom javascript to client
		 * @param string $code
		 * @return array
		 */
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
		
		/**
		 * Get all events
		 * @param boolean $delete
		 * @return array
		 */
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