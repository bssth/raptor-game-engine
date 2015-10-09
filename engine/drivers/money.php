<?php

/*
	@last_edit 22.08.2015
	@last_autor Mike
	@comment Интерфейс денежной системы.
*/

class moneyDriver 
{

    function actionIndex()
    {
        $params = Raptor::ModConfig('currency');
        $main = new Templater;
        $main->import("boxes/money_page.tpl");
        $main->setvar("%URL%", "http://" . $GLOBALS['url']);
        $main->setvar("%STORAGE_TPL_URL%", "/storage/tpl");
        $main->setvar("%YEAR%", date("Y"));
        $main->setvar("%CSS%", "<style>" . templater("game.css", array("%ROOT%" => "/storage/tpl")) . "</style>");
        $main->setvar("%GAME_TITLE%", $GLOBALS['name']);
        $main->setvar("%STORAGE_STATIC_URL%", "/storage/static");
        $result = '';
        foreach ($params as $key => $value) 
		{
            if (!is_array($value)) 
			{
                continue;
            }
            $result .= templater("boxes/money_list.tpl", array("%NAME%" => $value['name'], "%IMG%" => $value['img'], "%COUNT%" => char()->$key));
        }
        $main->setvar("%CONTENT%", $result);
        $main->renderEcho();
    }

}
