<?php

/*
	** @last_edit 23.08.2015
	** @last_autor Mike
	** @comment Система кэширования. Используется MemCache, а также файловый кэш как альтернатива
*/

class Cache 
{

    private static $cnt = false;
    private static $conn = null;

    private static function connect()
    {
        if (self::$cnt == false) 
		{
            if (isset($GLOBALS['memcache_ip']) and ! empty($GLOBALS['memcache_ip'])) 
			{
                self::$conn = new Memcache;
                self::$conn->connect($GLOBALS['memcache_ip'], $GLOBALS['memcache_port']) or raptor_warning("Can't connect memcache at " . $GLOBALS['memcache_ip'] . ":" . $GLOBALS['memcache_port'], true);
                self::$cnt = true;
            } 
			else
			{
                self::$cnt = false;
				self::$conn = new FileCache;
            }
        } 
		else 
		{
            return false;
        }
    }

    public static function get($key)
    {
        self::connect();
        return self::$conn->get($GLOBALS['database'] . "_" . $key);
    }

    public static function flush()
    {
        self::connect();
        return self::$conn->flush();
    }

    public static function replace($key, $var, $lifetime)
    {
        self::connect();
        return self::$conn->replace($GLOBALS['database'] . "_" . $key, $var, 0, $lifetime);
    }

    public static function set($key, $value, $lifetime)
    {
        self::connect();
        return self::$conn->set($GLOBALS['database'] . "_" . $key, $value, 0, $lifetime);
    }

}
