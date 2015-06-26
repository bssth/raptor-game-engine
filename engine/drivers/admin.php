<?php

class adminDriver {

    function __call($func, $args)
    {
		$func = strtolower(str_replace("action", "", $func));
		if(!file_exists(CACHE_ROOT . SEPARATOR . "installed.cache") and $func != 'install') { header("Location: /admin/install"); die(); }
		if(!isset($_SESSION['cid'])) { die("403 Forbidden"); }
        $char = new Char($_SESSION['cid']);
        if ($char->admin<1 and !in_array($func, $char->perms) and file_exists(CACHE_ROOT . SEPARATOR . "installed.cache")) {
            die("403 Forbidden");
        }
        include_once(ADMIN_ROOT . SEPARATOR . "header.inc.php");
        if (strstr($func, 'ext_')) {
            include_once(MODS_ROOT . SEPARATOR . trim($func, 'ext_') . SEPARATOR . trim($func, 'ext_') . ".admin.php");
        } else {
            include_once(ADMIN_ROOT . SEPARATOR . $func . ".php");
        }
        include_once(ADMIN_ROOT . SEPARATOR . "footer.inc.php");
    }

}