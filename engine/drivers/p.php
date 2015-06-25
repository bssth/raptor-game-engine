<?php

class pDriver {

    function actionIndex()
    {
        if (!isset($_SESSION['id'])) {
            header("Location: /");
            die();
        }
        if (!isset($_SESSION['cid'])) {
            header("Location: /cabinet");
            die();
        }

        $main = new Templater;
        $main->import("interface/game.tpl");
        $main->setvar("%URL%", "http://" . $GLOBALS['url']);
        $main->setvar("%STORAGE_TPL_URL%", "/storage/tpl");
        $main->setvar("%YEAR%", date("Y"));
        $main->setvar("%CSS%", "<style>" . templater("css/game.css", array("%ROOT%" => "/storage/tpl")) . "</style>");
        $main->setvar("%GAME_TITLE%", $GLOBALS['name']);
        $main->setvar("%STORAGE_STATIC_URL%", "/storage/static");
        $main->setvar("%GUI%", template("interface/GUI.tpl"));
        $main->setvar("%CHATBOX%", template("boxes/chat.tpl"));
        $main->renderEcho();
    }

}
