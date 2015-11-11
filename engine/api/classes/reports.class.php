<?php
	
	/* 
		@comment Peпорты игроков
		@last_edit 11.10.2015 by Mike
	*/
	
	class Reports
	{
		public static function get()
		{
			return Database::Get("reports", array());
		}
		
		public static function add($name, $text)
		{
			return Database::Insert("reports", array("author" => $name, "message" => $text, "date" => raptor_date()));
		}
	}