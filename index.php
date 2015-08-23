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

if(file_exists(SITE_ROOT . "/engine/cache/installed.cache") or file_exists(SITE_ROOT . "/engine/config.php")) 
{ 
	require_once(SITE_ROOT . "/engine/config.php");
}
else 
{
	$GLOBALS['database'] = "__temp";
	$GLOBALS['debug'] = false;
	$GLOBALS['url'] = $_SERVER['SERVER_NAME'];
}
require_once(SITE_ROOT . "/engine/api.php");
require_once(SITE_ROOT . "/engine/router.php");

Router::Start();

?>
