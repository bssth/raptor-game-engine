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
			
			try {
				(new \Auth\Char($_SESSION['cid']))->setOnline();
			}
			catch(\Raptor\Exception $e) {
				header('Location: /cabinet');
			}
			
			return (new \Raptor\Templater('gui'))->render();
		}
	}