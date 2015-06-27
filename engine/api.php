<?php

@session_start();

if($GLOBALS['driver'] == "messager" or $GLOBALS['driver'] == "api") {
	define("HIDE_ERRORS", 1);
	error_reporting(0);
	$GLOBALS['debug'] = false;
}
if(defined("HIDE_ERRORS")) {
	error_reporting(0);
}

if (!defined("WEBSITE")) {
    die("Hacking attempt");
}
if (!defined('ENGINE_ROOT')) {
    define("SEPARATOR", "/"); # Directory separator
    if (!defined('SITE_ROOT')) {
        define('SITE_ROOT', __DIR__ . '/..');
    }
    define("ENGINE_ROOT", __DIR__); # Root of 'engine' directory
    define("CACHE_ROOT", ENGINE_ROOT . SEPARATOR . "cache"); # Root of 'cache' directory
    define("STORAGE_ROOT", SITE_ROOT . SEPARATOR . "storage"); # Root of 'engine' directory
    define("STATIC_ROOT", STORAGE_ROOT . SEPARATOR . "static"); # Root of 'engine' directory
    define("API_ROOT", ENGINE_ROOT . SEPARATOR . "api"); # Root of 'api' directory
    define("ADMIN_ROOT", ENGINE_ROOT . SEPARATOR . "admin"); # Root of 'admin' directory
    define("MODS_ROOT", ENGINE_ROOT . SEPARATOR . "mods"); # Root of 'api' directory
    define("SITE_URL", $_SERVER['SERVER_NAME']);
    define("TEMPLATE_ROOT", ENGINE_ROOT . SEPARATOR . "templates");
    define("SCRIPTS_ROOT", ENGINE_ROOT . SEPARATOR . "scripts");
}
@include_once(API_ROOT . '/classes/database.class.php');
$cursor = Database::GetOne("config", array("active" => '1'));
if (empty($cursor['active']) and $GLOBALS['debug'] == false and file_exists(CACHE_ROOT . SEPARATOR . "installed.cache")) {
    die("Cannot load configuration");
}
if(is_array($cursor)) {
	$GLOBALS = array_merge($GLOBALS, $cursor);
}
spl_autoload_register('loadclass');
/* function loadclass($class) {
  @include_once(API_ROOT . '/classes/' . strtolower($class) . ".class.php");
  @include_once(API_ROOT . '/classes/' . $class . ".class.php");
  @include_once(ENGINE_ROOT . '/drivers/' . $class . ".php");
  foreach ($GLOBALS['modules'] as $module) {
  @include_once(MODS_ROOT . SEPARATOR . $module . SEPARATOR . strtolower($class) . ".class.php");
  @include_once(MODS_ROOT . SEPARATOR . $module . SEPARATOR . $class . ".class.php");
  }
  } */
function loadclass($class)
{
    if (!@include_once(API_ROOT . '/classes/' . strtolower($class) . ".class.php")) {
        if (!@include_once(API_ROOT . '/classes/' . $class . ".class.php")) {
            if (!@include_once(ENGINE_ROOT . '/drivers/' . $class . ".php")) {
                foreach ($GLOBALS['modules'] as $module) {
                    @include_once(MODS_ROOT . SEPARATOR . $module . SEPARATOR . strtolower($class) . ".class.php");
                    @include_once(MODS_ROOT . SEPARATOR . $module . SEPARATOR . $class . ".class.php");
                }
            }
        }
    }
}
function raptor_error_handler($errno, $errstr, $errfile, $errline)
{
	Database::Insert("errors", array("text" => $errstr, "date" => raptor_date(), "file" => $errfile, "line" => $errline));
	if(defined("HIDE_ERRORS")) { return false; }

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

if(!defined("HIDE_ERRORS")) {
	set_error_handler("raptor_error_handler");
}

include_once(API_ROOT . "/abstract.php");
include_once(API_ROOT . "/functions.php");
include_once(API_ROOT . "/defines.php");
if(isset($GLOBALS['modules'])) {
	foreach ($GLOBALS['modules'] as $module) {
		@include_once(MODS_ROOT . SEPARATOR . $module . SEPARATOR . "global.php");
	}
}
if(isset($_SESSION['cid'])) {
	if(is_object($_SESSION['cid'])) {
		__toString($_SESSION['cid']);
	}
	global $char;
	$char = new Char($_SESSION['cid']);
	$char->setOnline();
	eval( implode(" ", check_player_events($_SESSION['cid'], false, true)['eval']) );
}
if(isset($_SESSION['id'])) {
	if(is_object($_SESSION['id'])) {
		__toString($_SESSION['id']);
	}
	global $player;
	$player = new Player($_SESSION['id']);
}
eval(getScript('main'));
call_user_func("scriptEngineInit");
checkTimers();
?>
