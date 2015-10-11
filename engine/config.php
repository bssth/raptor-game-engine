<?php

/*
	Строка ниже определяет базу данных MongoDB, к которой будет подключаться ваша игра
*/

$GLOBALS['database'] = "raptor_dev";

/*
	Если нужно указать хост, пользователя и пароль базы данных - раскомментируйте код ниже и укажите их
*/

//$GLOBALS['database_host'] = false;
//$GLOBALS['database_user'] = false;
//$GLOBALS['database_password'] = false;

/*
	Укажите IP и порт веб-сокет сервера. Обычно IP совпадает с IP игры - оставьте всё как есть. 
	Если ваша игра не использует веб-сокеты (например, она текстовая или WAP), эти данные заполнять не надо, можно лишь указать ip - 0.0.0.0
	В большинстве случаев следует оставить как есть
*/

$GLOBALS['socket_ip'] = $_SERVER['SERVER_ADDR']; 
$GLOBALS['socket_port'] = 8873;

/*
	Если вы хотите использовать MemCache для кэширования данных и сохранения временных переменных - раскомментируйте строки ниже и укажите IP и порт
	В противном случае для кэша будут использоваться обычные файлы
*/

$GLOBALS['memcache_ip'] = '127.0.0.1';
$GLOBALS['memcache_port'] = 11211;

/*
	Строки ниже определяют режим отладки. В большинстве случаев их следует оставить как есть
*/

if(defined("MODE")) {
	$GLOBALS['debug'] = (MODE=='dev');
	error_reporting( MODE=='dev' ? E_ALL : 0 );
}
else {
	$GLOBALS['debug'] = false;
	error_reporting( E_COMPILE_ERROR );
}

/*
	Следующие строки до следующего комментария можно удалить, если вы хотите задать URL строго вручную
	Для этого надо использовать строгое присвоение: $GLOBALS['url'] = 'example.com';
	В противном случае будет использоваться авто-определение
*/

if(isset($_SERVER['SERVER_NAME'])) {
	$GLOBALS['url'] = $_SERVER['SERVER_NAME'];
}
elseif(isset($_SERVER['SERVER_ADDR'])) {
	$GLOBALS['url'] = $_SERVER['SERVER_ADDR'];
}
else {
	$GLOBALS['url'] = '0.0.0.0';
}

/**
 * Строки ниже определяют использование почты
 * Доступные методы отправки почты
 * 
 * SMTP
 * Mail
 * 
 * @global string $GLOBALS['mail_method']
 * @name $mail_method 
 * @todo Другие способы
 */
$GLOBALS['mail_method'] = 'mail';

$GLOBALS['smtp']['smtp_username'] = 'mhitariangor@yandex.ru';  //Смените на имя своего почтового ящика.
$GLOBALS['smtp']['smtp_port'] = 465; // Порт работы. Не меняйте, если не уверены.
$GLOBALS['smtp']['smtp_host'] = 'smtp.yandex.ru';  //сервер для отправки почты
$GLOBALS['smtp']['smtp_password'] = '20072006a';  //Измените пароль
$GLOBALS['smtp']['smtp_debug'] = true;  //Если Вы хотите видеть сообщения ошибок, укажите true вместо false
$GLOBALS['smtp']['smtp_charset'] = 'windows-1251'; //кодировка сообщений. (или UTF-8, итд)
$GLOBALS['smtp']['smtp_from'] = 'Leon1010';

/*
	Если вы хотите использовать MySQL, укажите ваши данные ниже
	Это не заменит MongoDB, но в редкостных случаях может использоваться как альтернатива или хранилище
*/

$GLOBALS['mysql_data'] = array(
    'host' => 'localhost',
    'user' => 'test',
    'password' => '123123123',
    'db' => 'test'
);

/*
	Если установить значение следующей переменной в true, пользовательский роутинг будет недоступен
	Другими словами, нельзя будет указывать driver и action явно: ?driver=test&action=test
	Естественно, стандартный роутинг будет доступен: /test/test
	Подробнее читайте в документации
*/

$GLOBALS['disable_user_routing'] = false;