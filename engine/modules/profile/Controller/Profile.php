<?php
	
	namespace Controller;
	
	class Profile
	{
		public function __call($k, $v)
		{
			if(!isset($_SESSION['cid'])) {
				\Raptor\Core::web_error(403);
			}
			
			$id = strtolower(str_replace('action', '', $k));
			if(empty($id) or $id === 'index') {
				\Raptor\Core::web_error(404);
			}
			
			try {
				$char = new \Auth\Char($id);
				$current = new \Auth\Char($_SESSION['cid']);
			}
			catch(\Raptor\Exception $e) {
				\Raptor\Core::web_error(404);
			}
			
			try {
				$player = (new \Auth\Player($char->player));
			}
			catch(\Raptor\Exception $e) {
				\Raptor\Core::web_error(500);
			}
			
			$is_owner = ( (isset($_SESSION['cid'])) and ((string)$_SESSION['cid'] === (string)$id) ) or $current->checkPermission('admin.char');
			
			return (new \Raptor\Templater('profile'))->set('char', $char)->set('player', $player)->set('is_owner', $is_owner)->render();
		}
	}