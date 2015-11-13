<?php
	
	class WebApp
	{
		
		public static function __html($tpl)
		{
			die( file_get_contents( TEMPLATE_ROOT . SEPARATOR . 'errors' . SEPARATOR . $tpl . '.tpl' ) );
		}
		
		public static function internal()
		{
			self::__html('500');
		}
		
		public static function not_found()
		{
			self::__html('404');
		}
		
		public static function forbidden()
		{
			self::__html('403');
		}
		
	}