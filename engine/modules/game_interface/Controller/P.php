<?php
	
	namespace Controller;
	
	class P
	{
		public function actionIndex()
		{
			if(!isset($_SESSION['id']) or !isset($_SESSION['cid'])) {
				header('Location: /');
				return '';
			}
			
			return (new \Raptor\Templater('gui'))->render();
		}
	}