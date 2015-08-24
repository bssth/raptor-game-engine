<?php

/*
	@comment Главный файл API. Комментарии не требуются, включая last_edit
*/

if (strstr(@$_SERVER['REQUEST_URI'], "/messager") or strstr(@$_SERVER['REQUEST_URI'], "/api")) 
{
    define("HIDE_ERRORS", 1);
    error_reporting(0);
    $GLOBALS['debug'] = false;
}
if (defined("HIDE_ERRORS")) 
{
    error_reporting(0);
    $GLOBALS['debug'] = false;
}

if (!defined("WEBSITE")) 
{
    die("Hacking attempt");
}
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
                foreach ($GLOBALS['modules'] as $module) 
				{
                    @include_once(MODS_ROOT . SEPARATOR . $module . SEPARATOR . strtolower($class) . ".class.php");
                    @include_once(MODS_ROOT . SEPARATOR . $module . SEPARATOR . $class . ".class.php");
                }
            }
        }
    }
}

include_once(API_ROOT . "/abstract.php");
include_once(API_ROOT . "/defines.php");

if(!defined("NOT_CLIENT_USE")) 
{
	new Sessions;
	@session_start();
}

if(is_string(Cache::get("config_main"))) 
{
	$cursor = Cache::get("config_main");
}

else 
{
	$cursor = Database::GetOne("config", array("active" => '1'));
	Cache::set("config_main", $cursor, 3600);
}

if (empty($cursor['active']) and $GLOBALS['debug'] == false and file_exists(CACHE_ROOT . SEPARATOR . "installed.cache")) 
{
    die("Cannot load configuration");
}

if (is_array($cursor)) 
{
    $GLOBALS = array_merge($GLOBALS, $cursor);
}


function raptor_error_handler($errno, $errstr, $errfile, $errline)
{
    #Database::Insert("errors", array("text" => $errstr, "date" => raptor_date(), "file" => $errfile, "line" => $errline));
    log_error("[$errno] $errstr (file: $errfile, line $errline) \n");
    if (defined("HIDE_ERRORS")) 
	{
        return false;
    }

    switch ($errno)
    {
        case E_USER_ERROR:
            echo "<b>ERROR</b> [$errno] $errstr<br />\n";
            echo "  Фатальная ошибка в строке $errline файла $errfile";
            echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
            raptor_error($errstr, false);
            exit(1);
            break;
        case E_USER_WARNING:
            echo "<b>WARNING</b> [$errno] $errstr<br />\n";
            raptor_warning($errstr, false);
            break;
        case E_WARNING:
            echo "<b>WARNING</b> [$errno] $errstr<br />\n";
            raptor_warning($errstr, false);
            break;
        case E_NOTICE:
            echo "<b>NOTICE</b> [$errno] $errstr<br />\n";
            raptor_notice($errstr, false);
            break;
        case E_USER_NOTICE:
            echo "<b>NOTICE</b> [$errno] $errstr<br />\n";
            raptor_notice($errstr, false);
            break;
        default:
            echo "Error $errno: $errstr<br />\n";
            break;
    }
    return true;
}

if (!defined("HIDE_ERRORS")) 
{
    set_error_handler("raptor_error_handler");
}

include_once(API_ROOT . "/functions.php");

if (isset($GLOBALS['modules'])) 
{
    foreach ($GLOBALS['modules'] as $module) 
	{
        if (!file_exists(MODS_ROOT . SEPARATOR . $module . SEPARATOR . "global.php")) 
		{
            continue;
        }
        @include_once(MODS_ROOT . SEPARATOR . $module . SEPARATOR . "global.php");
    }
}

if(!defined("NOT_CLIENT_USE") and defined("CLIENT_USE")) 
{
	if (isset($_SESSION['cid'])) 
	{
		if (is_object($_SESSION['cid'])) 
		{
			$_SESSION['cid'] = __toString($_SESSION['cid']);
		}
		$GLOBALS['chars'][$_SESSION['cid']] = new Char($_SESSION['cid']);
		$GLOBALS['chars'][$_SESSION['cid']]->setOnline();
		$ev = check_player_events($_SESSION['cid'], false, true);
		if(!empty($ev['eval'])) 
		{
			eval(implode(" ", $ev['eval']));
		}
	}
	if (isset($_SESSION['id'])) 
	{
		if (is_object($_SESSION['id'])) 
		{
			$_SESSION['id'] = __toString($_SESSION['id']);
		}
		global $player;
		$player = new Player($_SESSION['id']);
	}
	eval(getScript('main'));
	call_user_func("scriptEngineInit");
	checkTimers();
}

?>
