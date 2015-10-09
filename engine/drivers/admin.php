<?php

/*
	@last_edit 22.08.2015
	@last_autor Mike
	@comment Драйвер админ-панели, который подгружает код из папки /engine/admin
	@todo Меньше костылей, больше проверок
*/

class adminDriver {

    function __call($func, $args)
    {
		$func = strtolower(str_replace("action", "", $func));
		if(!file_exists(CACHE_ROOT . SEPARATOR . "installed.cache") and $func != 'install') 
		{ 
			Raptor::Redirect('/admin/install');
		}
        if (!char()->canUseAdmin($func)) 
		{
            die("403 Forbidden");
        }
		// запасной костыль
        if(!isset($_SESSION['cid']) and file_exists(CACHE_ROOT . SEPARATOR . "installed.cache")) 
		{ 
			die("403 Forbidden"); 
		}
		
        include_once(ADMIN_ROOT . SEPARATOR . "header.inc.php");
		
        if (strstr($func, 'ext_'))
		{
            include_once(MODS_ROOT . SEPARATOR . trim($func, 'ext_') . SEPARATOR . trim($func, 'ext_') . ".admin.php");
        } 
		else
		{
            include_once(ADMIN_ROOT . SEPARATOR . $func . ".php");
        }
		
        include_once(ADMIN_ROOT . SEPARATOR . "footer.inc.php");
    }

}
