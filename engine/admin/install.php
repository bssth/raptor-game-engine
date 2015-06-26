<?php
if(file_exists(CACHE_ROOT . SEPARATOR . "installed.cache")) 
{ 
	die('<script>location.href = "/admin/index";</script> Движок уже установлен'); 
}

switch ($_GET['step']) {
    case 1:
        if (class_exists("MongoClient")) {
            echo '<div class="alert alert-success">Класс MongoClient доступен</div>';
        } else {
            echo '<div class="alert alert-danger">Класс MongoClient недоступен (критично!); <a href="http://php.net/manual/ru/mongo.installation.php">Узнайте, как установить</a></div>';
        }
        if (class_exists("Memcache")) {
            echo '<div class="alert alert-success">Класс MemCache доступен</div>';
        } else {
            echo '<div class="alert alert-danger">Класс MemCache недоступен (не критично)</div>';
        }
        if (function_exists("mysqli_connect")) {
            echo '<div class="alert alert-success">Расширение MySQLi доступно</div>';
        } else {
            echo '<div class="alert alert-danger">Расширение MySQLi недоступно (не критично)</div>';
        }
        if (ini_get('allow_url_fopen')) {
            echo '<div class="alert alert-success"><b>allow_url_fopen</b> допускается</div>';
        } else {
            echo '<div class="alert alert-danger">Ваш сервер не допускает работы с удалёнными файлами. Это может привести к проблемам с API. Измените опцию <b>allow_url_fopen</b> в php.ini</div>';
        }
        echo '<p><a href="?step=2" class="btn btn-primary btn-lg" role="button">Конфигурация »</a></p>';
        break;
    case 2:
        if (file_exists(ENGINE_ROOT . SEPARATOR . "config.php")) {
            echo '<div class="well">Теперь найдите файл <b>config.php</b> в папке <b>engine</b>. Найдите там строчку: <samp>$GLOBALS[\'database\'] = "game";</samp>. Вместо <samp>game</samp> введите название вашей базы данных MongoDB. После этого можете переходить к следующему этапу.</div>
			<p><a href="?step=3" class="btn btn-primary btn-lg" role="button">Установка конфигурации »</a></p>';
        } else {
            echo '<div class="alert alert-danger"><b>config.php</b> отсутствует</div>';
        }
        break;
    case 3:
        echo '<div class="alert alert-danger">Reserved</div>';
        break;
    default:
        echo '<div class="jumbotron"><h2>Приветствуем вас!</h2><p>Данный мастер позволит вам установить игровой движок. Перед установкой убедитесь, что загрузили файлы-скрипты полностью.</p><p><a href="?step=1" class="btn btn-primary btn-lg" role="button">Проверка требований »</a></p></div>';
        break;
}