<?php
	
	namespace Controller;
	
	class Admin
	{
		
		public function __call($f, $a)
		{
			$act = strtolower(str_replace('action', '', $f));
			if(empty($act)) $act = 'index';
			$char = new \Auth\Char($_SESSION['cid']);
			if($char->checkPermission('admin.' . $act) or $char->checkPermission('admin.all'))
			{
				try {
					$action = (new \Raptor\Templater('admin_' . $act))->render();
				}
				catch(\Raptor\Exception $e) {
					return '<h1>404 Not Found</h1>';
				}
				if(isset($_GET['embed']))
				{
					return $action;
				}
				return (new \Raptor\Templater('admin'))->set('char', $char)->set('menu', $this->getMenu())->set('content', $action)->render();
			}
			else
			{
				return '<h1>403 Forbidden</h1>';
			}
		}
		
		protected function getMenu()
		{
			$test = \Database\Cache::get('admin_menu');
			if(is_array($test)) {
				return $test;
			}
			
			$data = \Database\Current::getAll('amenu', array());
			#\Database\Cache::set('admin_menu', $data, null, 3600); // todo
			return $data;
		}
		
	}