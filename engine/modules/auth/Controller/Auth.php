<?php 
	/**
	 * Controller provides client authorization
	 */
	 
	namespace Controller;
	
	class Auth
	{
		public function actionLogin()
		{
			if(!isset($_REQUEST['login']) or !isset($_REQUEST['password'])) {
				header('Location: /index?error=empty');
				return '';
			}
			
			$result = \Auth\Player::select(array('login' => $_REQUEST['login'], 'password' => sha1($_REQUEST['password'])));

			if(is_null($result)) {
				header('Location: /index?error=data');
				return '';
			}
			
			$_SESSION['id'] = (string)$result->_id;
			$result->last_ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'error';
			$result->last_date = time();
			$result->domain = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'error';
			
			\Raptor\EventListener::invoke('login', $result->_id); 
			header('Location: /cabinet');
			return '';
		}
		
		public function actionLogout()
		{
			session_destroy();
			header('Location: /index');
			return '';
		}
		
		public function actionRegister()
		{
			if(!isset($_REQUEST['login']) or !isset($_REQUEST['password'])) {
				header('Location: /index?error=empty');
				return '';
			}
			
			if( \Auth\Player::register($_REQUEST['login'], $_REQUEST['password']) )
			{
				header('Location: /index?ok=1');
				return '';
			}
			
			header('Location: /index?error=data');
			return '';
		}
	}