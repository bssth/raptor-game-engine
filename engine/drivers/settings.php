<?php

/*
	@last_edit 22.08.2015
	@last_autor Mike
	@comment Драйвер настроек игрока
	@todo API
*/

class settingsDriver {

    function actionIndex()
    {
        $main = new Templater;
        $main->import("boxes/settings.tpl");
        $main->setvar("%URL%", "http://" . $GLOBALS['url']);
        $main->setvar("%STORAGE_TPL_URL%", "/storage/tpl");
        $main->setvar("%YEAR%", date("Y"));
        $main->setvar("%CSS%", "<style>" . templater("css/game.css", array("%ROOT%" => "/storage/tpl")) . "</style>");
        $main->setvar("%GAME_TITLE%", $GLOBALS['name']);
        $main->setvar("%STORAGE_STATIC_URL%", "/storage/static");
        $main->setvar("%CONTENT%", $result);
        $main->renderEcho();
    }

}
