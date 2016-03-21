<?php
	/**
	 * Main controller
	 */
	 
	namespace Controller;
	
	class Index
	{
		
		public function __call($m, $p)
		{
			return (new \Raptor\Templater('static_index'))->render();
		}
		
		public function actionCabinet()
		{
			return (new \Raptor\Templater('raptor_cabinet'))->render();
		}
		
		public function actionAdmin()
		{
			return (new \Raptor\Templater('raptor_admin'))->render();
		}
		
		public function actionPlay()
		{
			return (new \Raptor\Templater('raptor_game'))
				->set('client', (new \Raptor\Templater('client.js'))->render())
				->render();
		}
		
	}