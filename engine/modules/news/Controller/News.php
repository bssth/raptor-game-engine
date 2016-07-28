<?php
	
	namespace Controller;
	
	class News
	{
		public function actionIndex()
		{
			return (new \Raptor\Templater('news'))->set('list', \Mmorpg\News::getAll())->render();
		}
	}