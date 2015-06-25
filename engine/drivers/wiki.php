<?php

class wikiDriver {

    public function actionIndex()
    {
        $article = Database::GetOne('wiki_pages', array('type' => 'main'));
        //$side_menu = Database::GetOne('wiki_side_menu',array('status' => 1));
        $tpl = new Templater();
        $tpl->import('wiki/wiki.tpl');
        $tpl->setvar('%GAME_TITLE%', $GLOBALS['name']);
        $tpl->setvar("%YEAR%", date("Y"));
        $tpl->setvar("%CONTENT%", $article['content']);
        $tpl->setvar("%SIDE_MENU%", 'Тут будет кастомное меню');
        $tpl->renderEcho();
    }

}
