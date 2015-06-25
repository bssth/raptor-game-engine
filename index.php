<?php

define("WEBSITE", 1); # Anti-hacking

define("SITE_ROOT", __DIR__); # Root of website
/*
 * Следующие строки были перемещены в engine/api.php

  define("SEPARATOR", "/"); # Directory separator
  define("ENGINE_ROOT", SITE_ROOT . SEPARATOR . "engine"); # Root of 'engine' directory
  define("CACHE_ROOT", ENGINE_ROOT . SEPARATOR . "cache"); # Root of 'cache' directory
  define("STORAGE_ROOT", SITE_ROOT . SEPARATOR . "storage"); # Root of 'engine' directory
  define("STATIC_ROOT", STORAGE_ROOT . SEPARATOR . "static"); # Root of 'engine' directory
  define("API_ROOT", ENGINE_ROOT . SEPARATOR . "api"); # Root of 'api' directory
  define("ADMIN_ROOT", ENGINE_ROOT . SEPARATOR . "admin"); # Root of 'admin' directory
  define("MODS_ROOT", ENGINE_ROOT . SEPARATOR . "mods"); # Root of 'api' directory
  define("SITE_URL", $_SERVER['SERVER_NAME']);
  define("TEMPLATE_ROOT", ENGINE_ROOT . SEPARATOR . "templates");
  define("SCRIPTS_ROOT", ENGINE_ROOT . SEPARATOR . "scripts"); */

/**
 * Дополнение от 0paquity
 */
define("MODE", "production");
/**
 * Всего режимов работы будет два
 * dev - режим разработки
 * production - работающий прототип или конечный продукт
 * 
 * На данном этапе разработки мы будем использовать режим dev
 * Режимы в основном будут влиять на показ ошибок
 * Если в режиме production при вызове не правильной страницы
 * Будет показываться просто ошибка 404 или 302 (не суть)
 * То в режиме dev будет показываться что именно роутер не смог найти
 */

if(file_exists(SITE_ROOT . "/engine/cache/installed.cache") or file_exists(SITE_ROOT . "/engine/config.php")) { 
	require_once(SITE_ROOT . "/engine/config.php");
}
else {
	$GLOBALS['database'] = "__temp";
	$GLOBALS['debug'] = false;
	$GLOBALS['url'] = $_SERVER['SERVER_NAME'];
}
require_once(SITE_ROOT . "/engine/api.php");
require_once(SITE_ROOT . "/engine/router.php");

Router::Start();
?>