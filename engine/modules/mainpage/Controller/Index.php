<?php 

	namespace Controller;
	
	class Index
	{
		
		public function actionIndex()
		{
			if(isset($_SESSION['id'])) {
				header('Location: /cabinet');
				return '';
			}
			
			\Raptor\EventListener::invoke('mainpage'); 
			return (new \Raptor\Templater('mainpage'))->set('error', isset($_REQUEST['error']))->render();
		}
		
	}