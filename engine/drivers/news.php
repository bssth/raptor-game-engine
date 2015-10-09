<?php
/*
	@last_edit 22.08.2015
	@last_autor Mike
	@comment Архив новостей, просмотр оных
	@todo API
*/

class newsDriver {

    function actionIndex()
    {
        $main = new Templater;
        $main->import("interface/news_list.tpl");
        $main->setvar("%URL%", "http://" . $GLOBALS['url']);
        $main->setvar("%STORAGE_TPL_URL%", "/storage/tpl");
        $main->setvar("%YEAR%", date("Y"));
        $main->setvar("%GAME_TITLE%", $GLOBALS['name']);
        $main->setvar("%STORAGE_STATIC_URL%", "/storage/static");

        $news = News::get()->sort(array('date' => -1));
        $html = '';
        foreach ($news as $array) 
		{
            $html .= templater("interface/news.tpl", array(
                "%SUBJECT%" => $array['title'],
                "%DATE%" => $array['date'],
                "%ANNOUNCE%" => $array['short'],
                "%LINK_MORE%" => "http://" . $GLOBALS['url'] . "/news/read?id=" . $array['_id'],
                "%ID%" => $array['_id'])
            );
        }

        $main->setvar("%CONTENT%", $html);

        $main->renderEcho();
    }

    function actionRead()
    {
        $main = new Templater;
        $main->import("interface/news_list.tpl");
        $main->setvar("%URL%", "http://" . $GLOBALS['url']);
        $main->setvar("%STORAGE_TPL_URL%", "/storage/tpl");
        $main->setvar("%YEAR%", date("Y"));
        $main->setvar("%GAME_TITLE%", $GLOBALS['name']);
        $main->setvar("%STORAGE_STATIC_URL%", "/storage/static");

        $array = News::get($_GET['id']);
        $html = templater("interface/news_full.tpl", array(
            "%SUBJECT%" => $array['title'],
            "%DATE%" => $array['date'],
            "%ANNOUNCE%" => $array['short'],
            "%TEXT%" => $array['full'],
            "%ID%" => $array['_id'])
        );

        $main->setvar("%CONTENT%", $html);

        $main->renderEcho();
    }

}
