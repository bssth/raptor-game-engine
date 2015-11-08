<?php 

/*
	@last_edit 13.10.2015 by Mike
	@comment Driver called when storage element wasnt found
*/

class storageDriver() 
{
	function __call() 
	{
		echo Raptor::get_string('bad_storage');
	}
}