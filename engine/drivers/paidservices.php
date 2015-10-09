<?php

/*
	@last_edit 22.08.2015
	@last_autor Mike
	@comment Платные услуги. Иногда система даёт сбои, но мы уже отстреливаем возможные ошибки.
	@todo Бесперебойная работа
*/

class paidservicesDriver 
{

    function actionIndex() 
	{
		$params = Raptor::ModConfig('mod_paidservice');
        $main = new Templater;
        $main->import("boxes/ps_page.tpl");
        $main->setvar("%URL%", "http://" . $GLOBALS['url']);
        $main->setvar("%STORAGE_TPL_URL%", "/storage/tpl");
        $main->setvar("%YEAR%", date("Y"));
        $main->setvar("%CSS%", "<style>" . templater("css/game.css", array("%ROOT%" => "/storage/tpl")) . "</style>");
        $main->setvar("%GAME_TITLE%", $GLOBALS['name']);
        $main->setvar("%STORAGE_STATIC_URL%", "/storage/static");
		$result = '';
		if(isset($_GET['buy'])) 
		{
			if(!isset($params[$_GET['buy']]['time'])) { $main->setvar("%CONTENT%", "<h2>Услуга не найдена</h2>"); $main->renderEcho(); return 1; }
			if(char()->$params[$_GET['buy']]['currency'] < $params[$_GET['buy']]['cost']) { $main->setvar("%CONTENT%", "<h2>Недостаточно денег</h2>"); $main->renderEcho(); return 1; }
			char()->giveMoney(-$params[$_GET['buy']]['cost'], $params[$_GET['buy']]['currency']);
			eval($params[$_GET['buy']]['eval_bought']);
			createTimer($_GET['buy'], $params[$_GET['buy']]['time'], $params[$_GET['buy']]['eval_expired']);
		}
        foreach($params as $key => $value) 
		{
			if(!is_array($value)) { continue; }
			$result .= templater("boxes/ps_list.tpl", array("%ID%" => $key, "%NAME%" => $value['name'], "%COST%" => $value['cost'], "%TIME%" => $value['time'], "%CURRENCY%" => Database::GetOne("config", array("mod" => "currency"))[$value['currency']]['name'], "%TIME%" => $value['time']));
		}
		$main->setvar("%CONTENT%", $result);
        $main->renderEcho();
    }

}
