<?php
	
	/* 
		@comment ������� �������
		@last_edit 10.10.2015 by Mike
	*/
	
	class Reports
	{
		public static function get()
		{
			return Database::Get("reports", array())
		}
	}