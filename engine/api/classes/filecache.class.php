<?php
/*
	** @last_edit 23.08.2015 by Mike
	** @comment Файловый кэш на замену Memcache. Методы класса аналогичны
*/

class FileCache
{
	protected $dir = CACHE_ROOT;
	protected $memory;
	protected $prefix = 'fc_';
	protected $sufix = '.cache';
	
    function get($key)
    {
		if(!file_exists($this->dir . SEPARATOR . $this->prefix . $key . $this->sufix)) { return null; }
		$this->memory = file_get_contents($this->dir . SEPARATOR . $this->prefix . $key . $this->sufix);
		if(is_string($this->memory))
		{
			$array = unserialize($this->memory);
			if(isset($array['__lifetime']) and $array['__lifetime'] < time()) 
			{
				return null;
			}
			if(isset($array['__string']))
			{
				return $array['__string'];
			}
			unset($array['__lifetime']);
			return $array;
		}
		else
		{
			return null;
		}
    }

    function flush()
    {
        $files = scandir($this->dir);
		foreach($files as $file)
		{
			if(strstr($file, $this->prefix) and strstr($file, $this->sufix))
			{
				file_put_contents($this->dir . SEPARATOR . $file, '');
			}
			else
			{
				continue;
			}
		}
    }

    function replace($key, $var, $t, $lifetime)
    {
        if(file_exists($this->dir . SEPARATOR . $this->prefix . $key . $this->sufix))
		{
			$this->set($key, $value, $lifetime);
		}
		else
		{
			return false;
		}
    }

    function set($key, $value, $t, $lifetime)
    {
		if(is_array($value))
		{
			$value['__lifetime'] = time()+$lifetime;
			file_put_contents($this->dir . SEPARATOR . $this->prefix . $key . $this->sufix, serialize($value));
			return "array";
		}
		else
		{
			$array = array('__lifetime' => time()+$lifetime, '__string' => $value);
			file_put_contents($this->dir . SEPARATOR . $this->prefix . $key . $this->sufix, serialize($array));
			return "string";
		}
    }
}