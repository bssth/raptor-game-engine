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
			
			$result = \Auth\Player::select(array('login' => $_REQUEST['login'], 'password' => $_REQUEST['password']));

			if(is_null($result)) {
				header('Location: /index?error=data');
				return '';
			}
			
			$_SESSION['id'] = (string)$result->_id;
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
			return '';
		}
	}