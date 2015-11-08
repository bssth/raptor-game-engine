<?php

/*
	@last_edit 13.10.2015
	@comment External API driver (API main interance)
*/

class apiDriver 
{
	
	function __call($a1, $a2) 
	{
		Raptor::Header('Content-Type: application/json; charset=utf-8');
		
		if(!class_exists("ExtAPI"))
		{
			throw new Exception("Cannot find external API class. What happened?");
		}
		
		if(!isset($_GET['a']) and isset($GLOBALS['action']))
		{
			$_GET['a'] = $GLOBALS['action'];
		}
		
		if(strstr($_GET['a'], '.')) { $_GET['a'] = str_replace('.', '_', $_GET['a']); }
		
		if(isset($_GET['a']) and method_exists("ExtAPI", $_GET['a'])) 
		{
			echo ExtAPI::$_GET['a']($_GET);
		}
		else 
		{
			echo ExtAPI::_undefined_method($_GET);
		}
	}
	
}

