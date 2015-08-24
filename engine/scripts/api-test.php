<?php

/*
	@last_edit 22.08.2015
	@last_autor Mike
	@comment Тест API. Запускать через консоль
*/

define("NOT_CLIENT_USE", true);
define("WEBSITE", true);

include(__DIR__ . "/../config.php");
include(__DIR__ . "/../api.php");

echo 'Если вы не увидели никаких ошибок, а лишь эту строчку, значит API работает нормально';