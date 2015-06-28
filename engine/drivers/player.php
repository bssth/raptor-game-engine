<?php


class playerDriver {

    function __call($func, $args)
    {
        $func = str_replace("action", "", $func);
        if ($func != 'index') {
            $array = Database::GetOne("characters", array("name" => $func));
        } else {
            $array = Database::GetOne("characters", array("_id" => toId($_SESSION['cid'])));
            $func = $array['name'];
        }
        if (!isset($array['name'])) {
            die("<h1>Персонаж " . $func . " не найден</h1>");
        }
        $params = Database::GetOne("config", array("mod" => "params"));
        $main = new Templater;
        $main->import("interface/playerinfo.tpl");
        $main->setvar("%URL%", "http://" . $GLOBALS['url']);
        $main->setvar("%STORAGE_TPL_URL%", "/storage/tpl");
        $main->setvar("%YEAR%", date("Y"));
        $main->setvar("%CSS%", "<style>" . templater("css/game.css", array("%ROOT%" => "/storage/tpl")) . "</style>");
        $main->setvar("%GAME_TITLE%", $GLOBALS['name']);
        $main->setvar("%STORAGE_STATIC_URL%", "/storage/static");
        $main->setvar("%GUI%", template("interface/GUI.tpl"));
        $main->setvar("%CHATBOX%", template("boxes/chat.tpl"));
		$params_all = '';

        foreach ($array as $key => $value) {
            if (MongoReserved($key) or MongoReserved($value) or strstr($key, "p_")) {
                continue;
            }
            $main->setvar("%" . $key . "%", char()->$key);
        }

        foreach ($params as $key => $value) {
            if (!strstr($key, "p_")) {
                continue;
            }
			$v = char()->getParam($key);
            $main->setvar("%" . $key . "%", $v);
			$params_all .= '<p><b>'. $value['name'] .'</b>: '. $v .'</p>';
        }
		$main->setvar("%PARAMS_ALL%", $params_all);
        $main->renderEcho();
    }

}
