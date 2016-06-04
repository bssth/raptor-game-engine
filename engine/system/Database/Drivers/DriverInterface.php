<?php
	/**
	 * Interface for all database drivers
	 */
	
	namespace Database\Drivers;
	
	interface DriverInterface
	{
		public function __construct();
		public function lastError();
		public function numRows();
		public function getOne($table, $query);
		public function getAll($table, $query);
		public function insert($table, $array);
		public function update($table, $find, $apply);
	}