<?php
	/**
	 * Controller provides raptor api
	 */
	
	namespace Controller;
	
	class Api
	{
		
		public function __construct()
		{
			\Application\WebApp::i()->header('Content-Type: application/javascript'); 
			\Debug\Fuse::$empty_html = true;
		}
		
		public function actionIndex() 
		{
			return json_encode( array( 'code' => '404', 'error' => '404 Not Found' ) );
		}
		
		public function actionMethod($e, $c, $a, $method)
		{
		
			$method = explode('.', $method);
			
			if(!isset($method[0])) {
				return json_encode( array( 'code' => '404', 'error' => 'Method is not found' ) );
			}
			
			if(!isset($method[1])) {
				$method[1] = 'index';
			}
			
			$method[0] = '\\Api\\' . ucfirst($method[0]);
			
			if(!class_exists($method[0])) {
				return json_encode( array( 'code' => '404', 'error' => 'Method is not found' ) );
			}
			
			$cl = new $method[0];
			
			if(!method_exists($cl, $method[1])) {
				return json_encode( array( 'code' => '404', 'error' => 'Method is not found' ) );
			}
			
			$ans = $cl->$method[1](); 
			
			return is_array($ans) ? json_encode($ans) : $ans;
			
		}
		
	}
	