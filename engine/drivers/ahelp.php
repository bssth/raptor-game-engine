<?php

class ahelpDriver {

    function actionCmdlist()
    {
        readfile(CACHE_ROOT . SEPARATOR . "cmdlist.cache");
    }

}
