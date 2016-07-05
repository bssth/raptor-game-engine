<?php
	/**
	 * External API realization
	 */
	
	namespace Controller;
	
	class Api
	{
		public function __call($f, $a)
		{
			header('Content-Type: text/json');
			
			try 
			{
				$args = explode('.', $f);
				$act = is_string($args) ? $args : $args[0];
				
				$classname = '\\Extapi\\' . str_replace('action', '', $act);
				if(!class_exists($classname)) {
					return json_encode(['error' => '404']);
				}
				$obj = new $classname;
				if(!isset($args[1]) or (!method_exists($obj, $args[1]) and !method_exists($obj, '__call'))) {
					return json_encode(['error' => '404']);
				}
				return json_encode(call_user_func_array([$obj, $args[1]], $args));
			}
			catch(\Raptor\Exception $e) {
				return json_encode(['error' => '500']);
			}
			return;
		}
	}