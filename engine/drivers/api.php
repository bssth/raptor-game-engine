<?php

/*
	@last_edit 22.08.2015
	@last_autor Mike
	@comment Главный драйвер, работающий с внешним API
*/

class apiDriver {
	
	function __call($a1, $a2) 
	{
		header('Content-Type: application/json; charset=utf-8');
		
		if(!class_exists("ExtAPI"))
		{
			throw new Exception("Cannot find external API class. What happened?");
		}
		
		if(method_exists("ExtAPI", $_GET['a'])) 
		{
			echo ExtAPI::$_GET['a']($_GET);
		}
		else 
		{
			echo ExtAPI::_undefined_method();
		}
	}
}

?>