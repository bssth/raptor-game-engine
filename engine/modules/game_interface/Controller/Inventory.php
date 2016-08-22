<?php
	
	namespace Controller;
	
	class Inventory
	{
		public function actionIndex()
		{
			if(!isset($_SESSION['id']) or !isset($_SESSION['cid'])) {
				header('Location: /');
				return '';
			}
			
			try {
				$char = new \Auth\Char($_SESSION['cid']);
				$char->setOnline();
			}
			catch(\Raptor\Exception $e) {
				if(\Raptor\Config::debug == true)
					die((string)$e);
				else
					header('Location: /cabinet');
			}
			
			return (new \Raptor\Templater('inventory'))->set('char', $char)->render();
		}
	}