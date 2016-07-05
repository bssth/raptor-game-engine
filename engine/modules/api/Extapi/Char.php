<?php
	
	namespace Extapi;
	
	class Char
	{
		public function online()
		{
			if(!isset($_SESSION['cid'])) {
				return ['error' => '403'];
			}
			return ['list' => array_keys(\Auth\Char::onlineList())];
		}
		
		public function click()
		{
			if(!isset($_SESSION['cid']) or !isset($_REQUEST['char']) or !isset($_REQUEST['action'])) {
				return ['error' => '403'];
			}
			return ['events' => (string)\Raptor\EventListener::invoke('char_act', $_SESSION['cid'], $_REQUEST['char'], $_REQUEST['action'])];
		}
		
		public function setonline()
		{
			if(!isset($_SESSION['cid'])) {
				return ['error' => '403'];
			}
			
			$char = new \Auth\Char($_SESSION['cid']);
			return ['status' => $char->setOnline()];
		}
		
		public function getdata()
		{
			if(!isset($_SESSION['cid']) or !isset($_REQUEST['id'])) {
				return ['error' => '403'];
			}
			$char = new \Auth\Char($_REQUEST['id']);
			return ['data' => [
				'name' => (string)$char->name,
				'player' => (string)$char->player,
				'location' => (string)$char->location,
				'pos' => [(string)$char->pos_x, (string)$char->pos_y, (string)$char->pos_z],
				'virtual' => $char->vworld,
				'status' => (string)$char->status,
				'online' => (bool)$char->isOnline(),
				'info' => $char->info,
			]];
		}
	}