<?php

/*
	@comment Главный файл API. Комментарии не требуются, включая last_edit
*/

if (!defined('ENGINE_ROOT')) 
{
    define("SEPARATOR", "/"); # Directory separator
    if (!defined('SITE_ROOT')) 
	{
        define('SITE_ROOT', __DIR__ . '/..');
    }
    define("ENGINE_ROOT", __DIR__); # Root of 'engine' directory
    define("CACHE_ROOT", ENGINE_ROOT . SEPARATOR . "cache"); # Root of 'cache' directory
    define("STORAGE_ROOT", SITE_ROOT . SEPARATOR . "storage"); # Root of 'engine' directory
    define("STATIC_ROOT", STORAGE_ROOT . SEPARATOR . "static"); # Root of 'engine' directory
    define("API_ROOT", ENGINE_ROOT . SEPARATOR . "api"); # Root of 'api' directory
    define("ADMIN_ROOT", ENGINE_ROOT . SEPARATOR . "admin"); # Root of 'admin' directory
	define("LANG_ROOT", ENGINE_ROOT . SEPARATOR . "lang"); # Root of 'lang' directory
    define("MODS_ROOT", ENGINE_ROOT . SEPARATOR . "mods"); # Root of 'api' directory
    define("SITE_URL", @$_SERVER['SERVER_NAME']);
    define("TEMPLATE_ROOT", ENGINE_ROOT . SEPARATOR . "templates");
    define("SCRIPTS_ROOT", ENGINE_ROOT . SEPARATOR . "scripts");
    define("LOGS_ROOT", ENGINE_ROOT . SEPARATOR . "logs");
}

spl_autoload_register('loadclass');

function loadclass($class)
{
    if (!@include_once(API_ROOT . '/classes/' . strtolower($class) . ".class.php")) 
	{
        if (!@include_once(API_ROOT . '/classes/' . $class . ".class.php")) 
		{
            if (!@include_once(ENGINE_ROOT . '/drivers/' . $class . ".php")) 
			{
                foreach (Modules::i()->getModules() as $module) 
				{
                    @include_once(MODS_ROOT . SEPARATOR . $module . SEPARATOR . strtolower($class) . ".class.php");
                    @include_once(MODS_ROOT . SEPARATOR . $module . SEPARATOR . $class . ".class.php");
                }
            }
        }
    }
}

if (strstr(@$_SERVER['REQUEST_URI'], "/messager") or strstr(@$_SERVER['REQUEST_URI'], "/api")) 
{
    define("HIDE_ERRORS", 1);
    error_reporting(0);
    Raptor::Debug(true);
}
if (defined("HIDE_ERRORS")) 
{
    error_reporting(0);
    Raptor::Debug(false);
}

Raptor::AntiHack();

include_once(API_ROOT . "/abstract.php");
include_once(API_ROOT . "/defines.php");

if(!defined("NOT_CLIENT_USE")) 
{
	Raptor::Session();
}

$cursor = Raptor::Config(true, true);

if (!defined("HIDE_ERRORS")) 
{
    set_error_handler("Raptor::ErrorHandler");
}

include_once(API_ROOT . "/functions.php");

Raptor::InitModules($cursor['modules']);

if(!defined("NOT_CLIENT_USE") and defined("CLIENT_USE")) 
{
	Raptor::Launcher();
}

?>
