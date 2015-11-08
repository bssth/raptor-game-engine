<?php

define("WEBSITE", 1); # Anti-hacking
define("CLIENT_USE", 1);

define("SITE_ROOT", __DIR__); # Root of website

/**
 * Дополнение от 0paquity
 */
define("MODE", "production");
/**
 * Всего режимов работы два
 * dev - режим разработки
 * production - работающий прототип или конечный продукт
 */

if(MODE == 'cloud')
{
	error_reporting(0);
	define("HIDE_ERRORS", true);
	$GLOBALS['debug'] = false;
	require_once(SITE_ROOT . "/engine/cloudmgr.php");
}
elseif(file_exists(SITE_ROOT . "/engine/cache/installed.cache") or file_exists(SITE_ROOT . "/engine/config.php")) 
{ 
	if(MODE !== 'dev')
	{
		error_reporting(0);
		define("HIDE_ERRORS", true);
		$GLOBALS['debug'] = false;
	}
	require_once(SITE_ROOT . "/engine/config.php");
}
else 
{
	$GLOBALS['database'] = "__temp";
	$GLOBALS['debug'] = false;
	$GLOBALS['url'] = $_SERVER['SERVER_NAME'];
	error_reporting(0);
	define("HIDE_ERRORS", true);
}
require_once(SITE_ROOT . "/engine/api.php");
require_once(SITE_ROOT . "/engine/router.php");

Router::Start();

