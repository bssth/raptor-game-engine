<?php
	/**
	 * Class stores character`s, item`s and other parameters
	 */
	
	namespace Mmorpg;
	
	class Parameter
	{
		protected $id = null;
		protected $type = null;
		protected $params = null;
		protected $gap_cache = null;
		
		public function __construct($type, $id)
		{
			$this->id = $id;
			$this->type = $type;
			$this->params = $this->getParams();
		}
		
		public function __get($k)
		{
			return $this->getValue($k);
		}
		
		public function getParams()
		{
			$test = \Database\Cache::get($this->type . '_params_list');
			if(is_array($test) and count($test)) {
				return $test;
			}
			
			$data = \Database\Current::getAll($this->type . '_params', array());
			$real = [];
			foreach($data as $k => $v)
				$real[$v['param']] = $v;

			$data = $real;
			
			\Database\Cache::set($this->type . '_params_list', $data, null, 3600);
			return $data;
		}
		
		public function getAllParams()
		{
			if(!is_null($this->gap_cache)) {
				return $this->gap_cache;
			}
			
			$result = [];
			foreach($this->params as $k => $parm)
				$result[$parm['param']] = $this->getValue($parm['param']);

			
			$this->gap_cache = array('list' => $this->params, 'values' => $result);
			return $this->gap_cache;
		}
		
		public function getValue($param)
		{
			$arr = \Database\Cache::get($this->type . '_' . $this->id);
			
			if(!isset($this->params[$param]))
				return null;
			if(!isset($arr['info'][$param])) 
				return $this->params[$param]['default'];
			
			switch($this->params[$param]['type'])
			{
				case 'integer': return (int)$arr['info'][$param];
				case 'float': return (float)$arr['info'][$param];
				case 'string': return (string)$arr['info'][$param];
				case 'char': return (new \Auth\Char($arr['info'][$param]));
			}
			
			throw new \Raptor\Exception("Bad parameter {$param} type: {$this->params[$param]['type']}");
			return null;
		}
		
		public function setValue($param, $value)
		{
			$arr = \Database\Cache::get($this->type . '_' . $this->id);
			if(!is_array($arr)) return false;
			
			$arr['info'][$param] = $value;
			\Database\Cache::set($this->type . '_' . $this->id, $arr, null, 3600);

			switch($this->type) 
			{
				case 'char': $result = \Database\Current::update('characters', array('_id' => $this->id), $arr);
				default: $result = 0;
			}
			return ($result > 0) ? true : false;
		}
		
		public function __set($k, $v)
		{
			$this->setValue($k, $v);
		}
		
		public function __toString()
		{
			return (string)$this->id;
		}
	}