<?php

/*
	@last_edit 13.10.2015
	@comment User settings driver
	@todo API
*/

class settingsDriver 
{

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
