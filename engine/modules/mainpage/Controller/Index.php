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
			
			return (new \Raptor\Templater('mainpage'))->set('error', isset($_REQUEST['error']))->render();
		}
		
	}