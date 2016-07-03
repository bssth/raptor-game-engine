<?php 

	namespace Controller;
	
	class Cabinet
	{
		
		public function actionIndex()
		{
			if(!isset($_SESSION['id'])) {
				header('Location: /index');
				return '';
			}
			if(isset($_SESSION['cid'])) {
				header('Location: /p');
				return '';
			}
			
			$player = new \Auth\Player($_SESSION['id']);
			$chars = $player->getCharacters();
			
			return (new \Raptor\Templater('cabinet'))->set('player', $player)->set('chars', $chars)->set('error', isset($_REQUEST['error']))->render();
		}
		
		public function actionSelect()
		{
			if(!isset($_SESSION['id'])) {
				header('Location: /index');
				return '';
			}
			if(isset($_SESSION['cid'])) {
				header('Location: /p');
				return '';
			}
			if(!isset($_REQUEST['id'])) {
				header('Location: /cabinet?error=1');
				return '';
			}
			
			try {
				$char = new \Auth\Char($_REQUEST['id']);
			}
			catch(\Raptor\Exception $e) {
				\Raptor\Core::web_error(404);
			}
			
			if( $char->player === (string)$_SESSION['id'] )
			{
				$_SESSION['cid'] = $_REQUEST['id'];
				$char->setOnline();
				\Raptor\EventListener::invoke('char_log', $_REQUEST['id']); 
				header('Location: /cabinet');
				return '';
			}
			
			header('Location: /cabinet?error=1');
			return '';
		}
		
		public function actionNew()
		{
			if(!isset($_SESSION['id'])) {
				header('Location: /index');
				return '';
			}
			if(isset($_SESSION['cid'])) {
				header('Location: /p');
				return '';
			}
			if(!isset($_REQUEST['name'])) {
				header('Location: /cabinet?error=1');
				return '';
			}
			
			if( \Auth\Char::create($_REQUEST['name'], $_SESSION['id']) )
			{
				header('Location: /cabinet');
				return '';
			}
			
			header('Location: /cabinet?error=1');
			return '';
		}
		
	}