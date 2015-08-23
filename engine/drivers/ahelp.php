<?php

/*
	@last_edit 22.08.2015
	@last_autor Mike
	@comment Костыльная система списка команд RPG.JS
	@todo Предлагается к удалению и замене на нормальную систему
*/

class ahelpDriver 
{

    function actionCmdlist()
    {
        readfile(CACHE_ROOT . SEPARATOR . "cmdlist.cache");
    }

}
