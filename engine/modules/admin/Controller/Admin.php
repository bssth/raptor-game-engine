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
				$action = (new \Raptor\Templater('admin_' . $act))->render();
				return (new \Raptor\Templater('admin'))->set('content', $action)->render();
			}
			else
			{
				return '<h1>403 Forbidden</h1>';
			}
		}
		
	}