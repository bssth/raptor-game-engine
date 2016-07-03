<?php
	/**
	 * MongoDB driver for RAPTOR
	 */
	
	namespace Database\Drivers;
	
	class Mongo implements DriverInterface
	{
		protected $i = null;
		protected $last_cursor = null;
		
		public function __construct()
		{
			if(\Raptor\Config::db_user != null)
			{
				$this->i = new \MongoClient('mongodb://' . \Raptor\Config::db_user . ':' . \Raptor\Config::db_password . '@' . \Raptor\Config::db_host);
			}
			else
			{
				$this->i = new \MongoClient(\Raptor\Config::db_host);
			}
			
			$this->i = $this->i->selectDB(\Raptor\Config::db_database);
		}
		
		public function lastError()
		{
			return $this->i->lastError();
		}
		
		public function numRows()
		{
			if($this->last_cursor !== null)
			{
				return $this->last_cursor->count();
			}
			else
			{
				return 0;
			}
		}
		
		public function getOne($table, $query)
		{
			$tbl = $this->i->selectCollection($table);
			
			if(isset($query['_id']) and !is_object($query['_id'])) {
				$query['_id'] = new \MongoId($query['_id']);
			}

			return $tbl->findOne($query);
		}
		
		public function getAll($table, $query)
		{
			$tbl = $this->i->selectCollection($table);
			
			if(isset($query['_id']) and !is_object($query['_id'])) {
				$query['_id'] = new \MongoId($query['_id']);
			}
			
			return iterator_to_array($tbl->find($query));
		}
		
		public function insert($table, $array)
		{
			$tbl = $this->i->selectCollection($table);
			
			return $tbl->insert($array);
		}
		
		public function update($table, $find, $apply)
		{
			$tbl = $this->i->selectCollection($table);
			
			if(isset($find['_id']) and !is_object($find['_id'])) {
				$find['_id'] = new \MongoId($find['_id']);
			}
			if(isset($apply['_id']) and !is_object($apply['_id'])) {
				$apply['_id'] = new \MongoId($apply['_id']);
			}
			
			return $tbl->update($find, $apply)['nModified'];
		}
		
		public function remove($table, $query)
		{
			$tbl = $this->i->selectCollection($table);
			return $tbl->remove($query)['ok'];
		}		
	}