<?php
	/**
	 * Test API
	 */
	
	namespace Extapi;
	
	class Test 
	{
		public function __call($k, $v)
		{
			return ['answer' => true];
		}
	}