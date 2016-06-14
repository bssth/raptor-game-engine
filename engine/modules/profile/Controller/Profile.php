<?php
	
	namespace Controller;
	
	class Profile
	{
		public function __call($k, $v)
		{
			if(!isset($_SESSION['cid'])) {
				return '403 Forbidden';
			}
			
			$id = strtolower(str_replace('action', '', $k));
			if(empty($id) or $id === 'index') {
				return '404 Not Found';
			}
			
			try {
				$char = new \Auth\Char($id);
				$current = new \Auth\Char($_SESSION['cid']);
			}
			catch(\Raptor\Exception $e) {
				return '404 Not Found';
			}
			
			try {
				$player = (new \Auth\Player($char->player));
			}
			catch(\Raptor\Exception $e) {
				return '500 Internal Server Error';
			}
			
			$is_owner = ( (isset($_SESSION['cid'])) and ((string)$_SESSION['cid'] === (string)$id) ) or $current->checkPermission('admin.char');
			
			return (new \Raptor\Templater('profile'))->set('char', $char)->set('player', $player)->set('is_owner', $is_owner)->render();
		}
	}