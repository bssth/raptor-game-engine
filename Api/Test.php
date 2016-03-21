<?php
	/**
	 * Test api method
	 */
	
	namespace Api;
	
	class Test
	{
		public function index()
		{
			return array('answer' => 'Test OK');
		}
	}