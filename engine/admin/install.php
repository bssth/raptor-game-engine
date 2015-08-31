<?php
	
	if (!defined('ENGINE_ROOT') or !defined('SEPARATOR')) 
	{
		define("SEPARATOR", "/"); # Directory separator
		if (!defined('SITE_ROOT')) 
		{
			define('SITE_ROOT', __DIR__ . '/..');
		}
		define("ENGINE_ROOT", __DIR__); # Root of 'engine' directory
		define("CACHE_ROOT", ENGINE_ROOT . SEPARATOR . "cache"); # Root of 'cache' directory
		define("STORAGE_ROOT", SITE_ROOT . SEPARATOR . "storage"); # Root of 'engine' directory
		define("STATIC_ROOT", STORAGE_ROOT . SEPARATOR . "static"); # Root of 'engine' directory
		define("API_ROOT", ENGINE_ROOT . SEPARATOR . "api"); # Root of 'api' directory
		define("ADMIN_ROOT", ENGINE_ROOT . SEPARATOR . "admin"); # Root of 'admin' directory
		define("LANG_ROOT", ENGINE_ROOT . SEPARATOR . "lang"); # Root of 'lang' directory
		define("MODS_ROOT", ENGINE_ROOT . SEPARATOR . "mods"); # Root of 'api' directory
		define("SITE_URL", @$_SERVER['SERVER_NAME']);
		define("TEMPLATE_ROOT", ENGINE_ROOT . SEPARATOR . "templates");
		define("SCRIPTS_ROOT", ENGINE_ROOT . SEPARATOR . "scripts");
		define("LOGS_ROOT", ENGINE_ROOT . SEPARATOR . "logs");
	}
	
	$config = array();
	$config[] = array (
			  'mod' => 'auth',
			  'allowRegister' => '0',
			  'authType' => 'login',
			  'maxchars' => '4',
			  'start' => '5570740e01f12',
			  'start_x' => '303',
			  'start_y' => '213',
			  'start_dir' => 'bottom',
	);
	$config[] = array (
			  'mod' => 'locations',
			  '_onrun' => '[
			  "SHOW_TEXT: {\'text\': \'Добро пожаловать в вашу новую игру!\'}"
			]',
			  '_onsync' => '[
			]',
			  '_onshake' => '[
			]',
			  '5570740e01f12' => 
			  array (
				'name' => 'Стартовая локация',
				'name_en' => 'Starting Location',
				'map' => '1',
			  ),
			  '_graphic_characters' => '{
			"1": "event1.png",
			"2": "event2.png"
			}',
			  '_tilesets' => '{
			"1": "tileset.png"
			}',
			  '_music' => '{
			"1": "Iwan Gabovitch - Dark Ambience Loop.mp3"
			}',
			'_rpgjs_database' => '"autotiles":{
					"1":{
						"propreties":[[0,0],[0,15]],
						"autotiles":["2","3"],
					}
				},
			"tilesets": {
				"1": {
					"propreties":[[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[5,0],[5,0],[5],[5],[5],[],[],[],[null,15],[null,15],[null,15],[null,15],[null,15],[],[5],[5],[null,15],[0],[null,15],[5],[5],[],[null,15],[null,15],[null,15],[5],[null,15],[null,15],[null,15],[],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[],[],[],[5],[5],[5],[5],[],[],[],[],[null,15],[],[],[null,15],[],[],[],[],[null,15],[],[],[null,15],[],[],[5],[5],[5],[5],[5],[null,15],[],[null,15],[5],[5],[null,15],[null,15],[null,15],[null,15],[],[null,15],[null,15],[null,15],[],[],[],[null,15],[],[],[null,15],[null,15],[],[],[],[],[],[],[null,15],[null,15],[5],[5,0],[5,0],[5,0],[5,0],[5],[5],[5],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,0],[null,0],[null,15],[null,15],[null,15],[null,15],[],[],[],[],[],[],[null,15],[5],[],[],[],[],[],[],[null,15],[null,15],[],[],[],[],[5],[5],[5],[5],[],[],[],[],[5],[5],[5],[5],[5],[5],[null,15],[null,15],[5],[5],[5],[5],[null,15],[null,15],[],[],[],[null,15],[null,15],[],[5],[5],[null,15],[null,15],[null,15],[null,15],[],[],[null,15],[null,15],[],[],[null,15],[null,15],[null,15],[],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[],[],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[],[],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[],[],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[5],[5],[5],[5],[0],[],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[5,0],[5,0],[5,0],[5,0],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[null,15],[],[],[],[],[],[],[5],[5,0],[],[],[],[],[],[],[null,15],[null,15],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[null,15],[null,15],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[]],
					"graphic": "1"
				}
			},
			"map_infos": {
				"1": {
					"tileset_id": "1",
							"autotiles_id": "1",
			//		"bgm": "1"
				}
			}',
			  '_defines' => 'canvas: "gui",
			autoload: false,
			scene_path: "",
			tiled: true',
			  '_autotiles' => '{
			"2":"autotile1.png",
			"3":"autotile2.png"
			}',
	);
	
if(file_exists(CACHE_ROOT . SEPARATOR . "installed.cache")) 
{ 
	die('<script>location.href = "/admin/index";</script> Движок уже установлен'); 
}
error_reporting(0);
switch ($_GET['step']) {
    case 1:
        if (class_exists("MongoClient")) 
		{
            echo '<div class="alert alert-success">Класс MongoClient доступен</div>';
        } 
		else 
		{
            echo '<div class="alert alert-danger">Класс MongoClient недоступен (критично!); <a href="http://php.net/manual/ru/mongo.installation.php">Узнайте, как установить</a></div>';
        }
        if (class_exists("Memcache")) 
		{
            echo '<div class="alert alert-success">Класс MemCache доступен</div>';
        } 
		else 
		{
            echo '<div class="alert alert-danger">Класс MemCache недоступен (будут использоваться файлы)</div>';
        }
        if (function_exists("mysqli_connect")) 
		{
            echo '<div class="alert alert-success">Расширение MySQLi доступно</div>';
        } 
		else 
		{
            echo '<div class="alert alert-danger">Расширение MySQLi недоступно (не критично)</div>';
        }
        if (ini_get('allow_url_fopen')) 
		{
            echo '<div class="alert alert-success"><b>allow_url_fopen</b> допускается</div>';
        } 
		else 
		{
            echo '<div class="alert alert-danger">Ваш сервер не допускает работы с удалёнными файлами. Это может привести к проблемам с API. Измените опцию <b>allow_url_fopen</b> в php.ini</div>';
        }
        echo '<p><a href="?step=2" class="btn btn-primary btn-lg" role="button">Конфигурация »</a></p>';
        break;
    case 2:
        if (file_exists(ENGINE_ROOT . SEPARATOR . "config.php.dist")) 
		{
			if(!rename(ENGINE_ROOT . SEPARATOR . "config.php.dist", ENGINE_ROOT . SEPARATOR . "config.php"))
			{
				raptor_print('PGRpdiBjbGFzcz0id2VsbCI+0KHQtdC50YfQsNGBINCy0LDQvCDQv9GA0LXQtNGB0YLQvtC40YIg0LLQstC10YHRgtC4INC+0YHQvdC+0LLQvdGL0LUg0LTQsNC90L3Ri9C1INCx0LDQt9GLLiDQndCw0LnQtNC40YLQtSDQsiDQv9Cw0L/QutC1IGVuZ2luZSDRhNCw0LnQuyBjb25maWcucGhwLmRpc3Qg0Lgg0L/QtdGA0LXQuNC80LXQvdGD0LnRgtC1INC10LPQviDQsiBjb25maWcucGhwIDxicj4g0J/QvtGC0L7QvCDQvtGC0LrRgNC+0LnRgtC1INC70Y7QsdGL0Lwg0YLQtdC60YHRgtC+0LLRi9C8INGA0LXQtNCw0LrRgtC+0YDQvtC8INC4INCy0LLQtdC00LjRgtC1INGC0YDQtdCx0YPQtdC80YvQtSDQtNCw0L3QvdGL0LUsINGB0LvQtdC00YPRjyDQv9C+0LTRgdC60LDQt9C60LDQvCDQsiDRhNCw0LnQu9C1LiDQnNGLINC/0L7QtNC+0LbQtNGR0LwsINC/0L7QutCwINCy0Ysg0LfQsNC60L7QvdGH0LjRgtC1LCDQv9C+0YHQu9C1INC90LDQttC80LjRgtC1INC60L3QvtC/0LrRgyDQlNCw0LvRjNGI0LU8L2Rpdj4NCgkJCTxwPjxhIGhyZWY9Ij9zdGVwPTMiIGNsYXNzPSJidG4gYnRuLXByaW1hcnkgYnRuLWxnIiByb2xlPSJidXR0b24iPtCU0LDQu9GM0YjQtSDCuzwvYT48L3A+');
			}
			else 
			{
				raptor_print('PGRpdiBjbGFzcz0id2VsbCI+0KHQtdC50YfQsNGBINCy0LDQvCDQv9GA0LXQtNGB0YLQvtC40YIg0LLQstC10YHRgtC4INC+0YHQvdC+0LLQvdGL0LUg0LTQsNC90L3Ri9C1INCx0LDQt9GLLiDQndCw0LnQtNC40YLQtSDQsiDQv9Cw0L/QutC1IGVuZ2luZSDRhNCw0LnQuyBjb25maWcucGhwLCDQvtGC0LrRgNC+0LnRgtC1INC70Y7QsdGL0Lwg0YLQtdC60YHRgtC+0LLRi9C8INGA0LXQtNCw0LrRgtC+0YDQvtC8INC4INCy0LLQtdC00LjRgtC1INGC0YDQtdCx0YPQtdC80YvQtSDQtNCw0L3QvdGL0LUsINGB0LvQtdC00YPRjyDQv9C+0LTRgdC60LDQt9C60LDQvCDQsiDRhNCw0LnQu9C1LiDQnNGLINC/0L7QtNC+0LbQtNGR0LwsINC/0L7QutCwINCy0Ysg0LfQsNC60L7QvdGH0LjRgtC1LCDQv9C+0YHQu9C1INC90LDQttC80LjRgtC1INC60L3QvtC/0LrRgyDQlNCw0LvRjNGI0LU8L2Rpdj4NCgkJCTxwPjxhIGhyZWY9Ij9zdGVwPTMiIGNsYXNzPSJidG4gYnRuLXByaW1hcnkgYnRuLWxnIiByb2xlPSJidXR0b24iPtCU0LDQu9GM0YjQtSDCuzwvYT48L3A+');
			}
        } 
		else 
		{
            raptor_print('PGRpdiBjbGFzcz0iYWxlcnQgYWxlcnQtZGFuZ2VyIj48Yj5jb25maWcucGhwLmRpc3Q8L2I+INC+0YLRgdGD0YLRgdGC0LLRg9C10YIg0LIg0L/QsNC/0LrQtSBlbmdpbmUuINCj0LHQtdC00LjRgtC10YHRjCDQsiDRhtC10LvQvtGB0YLQvdC+0YHRgtC4INC00LDQvdC90YvRhS4g0JXRgdC70Lgg0LLRiyDRg9Cy0LXRgNC10L3Riywg0YfRgtC+INGE0LDQudC7INGC0LDQvCDQtdGB0YLRjCwg0L/QtdGA0LXQuNC80LXQvdGD0LnRgtC1INC10LPQviDQsiBjb25maWcucGhwINC4INC90LDQv9C+0LvQvdC40YLQtSwg0YHQu9C10LTRg9GPINC60L7QvNC80LXQvdGC0LDRgNC40Y/QvCDQsiDQutC+0LTQtTwvZGl2Pg==');
        }
        break;
    case 3:
		if (isset($_POST['name'])) 
		{
			$in = array_merge( array('modules'=>array(),'active'=>'1','id'=>uniqid()), $_POST );

			foreach($config as $as) 
			{
				Database::Insert("config", $as);
			}
			
			Database::Insert("scripts", array (
			  'name' => 'main',
			  'code' => 'ZnVuY3Rpb24gc2NyaXB0RW5naW5lSW5pdCgpIHsNCiAgcmV0dXJuIDE7DQp9DQoNCmZ1bmN0aW9uIG9uUGxheWVyTG9naW4oJGxvZ2luLCAkcGFzc3dvcmQsICRzdWNjZXNzKSB7DQogIHJldHVybiAxOw0KfQ0KDQpmdW5jdGlvbiBvblBsYXllclJlZ2lzdGVyKCRsb2dpbiwgJHBhc3N3b3JkLCAkZW1haWwpIHsNCiAgcmV0dXJuIDE7DQp9DQoNCmZ1bmN0aW9uIEV2ZW50VGltZXJFeHBpcmVkKCRpZCkgew0KICByZXR1cm4gMTsNCn0NCg0KZnVuY3Rpb24gVXNlSXRlbSgkaWQsICRpdGVtKSB7DQogIHJldHVybiAxOw0KfQ0KDQpmdW5jdGlvbiBvblJvdXRlZCgkZHJpdmVyLCAkYWN0aW9uLCAkbGluaykgew0KICByZXR1cm4gMTsNCn0NCg0KZnVuY3Rpb24gb25DbGllbnRDYWxsKCRpbnB1dCwgJHBhcmFtcykgew0KICByZXR1cm4gMTsNCn0NCg0KZnVuY3Rpb24gb25BcGlNZXRob2RDYWxsZWQoJG1ldGhvZCwgJHJlcXVlc3QpIHsNCiAgcmV0dXJuIGZhbHNlOw0KfQ0KDQpmdW5jdGlvbiBvbkRpYWxvZ1Jlc3BvbnNlKCRkaWFsb2dpZCwgJGFuc3dlcikgew0KICByZXR1cm4gMTsNCn0NCg0KZnVuY3Rpb24gb25QbGF5ZXJDb250ZXh0TWVudSgkbGlzdGl0ZW0sICR0YXJnZXQpIHsNCiAgcmV0dXJuIDE7DQp9',));
			echo "<div class='alert alert-success'>База данных заполнена. <a href='?step=4'>Перейти к последнему шагу</a></div>";
		}
		raptor_print('PGZvcm0gYWN0aW9uPSIiIG1ldGhvZD0iUE9TVCI+DQoJCTxkaXYgY2xhc3M9ImZvcm0tZ3JvdXAiPg0KCQkJPGxhYmVsPtCd0LDQt9Cy0LDQvdC40LUg0LjQs9GA0Ys8L2xhYmVsPg0KCQkJPGlucHV0IGNsYXNzPSJmb3JtLWNvbnRyb2wiIG5hbWU9Im5hbWUiIHZhbHVlPSIiPg0KCQk8L2Rpdj4NCgkJPGRpdiBjbGFzcz0iZm9ybS1ncm91cCI+DQoJCQk8bGFiZWw+0JLQtdGA0YHQuNGPINC40LPRgNGLPC9sYWJlbD4NCgkJCTxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiBuYW1lPSJ2ZXJzaW9uIiB2YWx1ZT0iIj4NCgkJPC9kaXY+DQoJCTxkaXYgY2xhc3M9ImZvcm0tZ3JvdXAiPg0KCQkJPGxhYmVsPlB1YmxpYyBLZXkgKNC/0YPQsdC70LjRh9C90YvQuSDQutC70Y7RhyDQtNC70Y8gQVBJKTwvbGFiZWw+DQoJCQk8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgbmFtZT0icHVibGljX2tleSIgdmFsdWU9IiI+DQoJCTwvZGl2Pg0KCQk8ZGl2IGNsYXNzPSJmb3JtLWdyb3VwIj4NCgkJCTxsYWJlbD5Qcml2YXRlIEtleSAo0L/RgNC40LLQsNGC0L3Ri9C5INC60LvRjtGHINC00LvRjyBBUEk7INC90LUg0YHQvtC+0LHRidCw0LnRgtC1INC10LPQviDRgdGC0L7RgNC+0L3QvdC40Lwg0LvQuNGG0LDQvCk8L2xhYmVsPg0KCQkJPGlucHV0IGNsYXNzPSJmb3JtLWNvbnRyb2wiIG5hbWU9InByaXZhdGVfa2V5IiB2YWx1ZT0iIj4NCgkJPC9kaXY+DQoJCTxidXR0b24gdHlwZT0ic3VibWl0IiBjbGFzcz0iYnRuIGJ0bi1kZWZhdWx0Ij7QodC+0YXRgNCw0L3QuNGC0Yw8L2J1dHRvbj4NCgk8L2Zvcm0+');
        break;
	case 4:
		if(isset($_POST['name'])) 
		{
			$id = Player::register($_POST['name'], $_POST['password'], $_POST['email']);
			Char::create(array('name'=>$_POST['name'],'player'=>$id,'about'=>$_POST['about'],'admin'=>'1'));
			if(file_put_contents(CACHE_ROOT . SEPARATOR . "installed.cache", "What are you looking for, admin?")) 
			{
				echo '<h3>Игра полностью установлена. <a href="/">Вход</a></h3>';
			}
			else 
			{
				echo '<h3>Ошибка при создании файла завершения установки. <a href="?step=4&file=1">Повторить попытку</a></h3>';
			}
		}
		if(isset($_GET['file'])) 
		{
			if(file_put_contents(CACHE_ROOT . SEPARATOR . "installed.cache", "What are you looking for, admin?")) 
			{
				echo '<h3>Игра полностью установлена. <a href="/">Вход</a></h3>';
			}
			else 
			{
				echo '<h3>Ошибка при создании файла завершения установки. <a href="?step=4&file=1">Повторить попытку</a></h3>';
			}
		}
		raptor_print('PGgzPtCS0LLQtdC00LjRgtC1INC00LDQvdC90YvQtSDQtNC70Y8g0LLQsNGI0LXQs9C+INC40LPRgNC+0LrQsCDQuCDQv9C10YDRgdC+0L3QsNC20LA8L2gzPjxmb3JtIGFjdGlvbj0iIiBtZXRob2Q9IlBPU1QiPg0KCQk8ZGl2IGNsYXNzPSJmb3JtLWdyb3VwIj4NCgkJCTxsYWJlbD7Qm9C+0LPQuNC9INC4INC40LzRjzwvbGFiZWw+DQoJCQk8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgbmFtZT0ibmFtZSIgdmFsdWU9IiI+DQoJCTwvZGl2Pg0KCQk8ZGl2IGNsYXNzPSJmb3JtLWdyb3VwIj4NCgkJCTxsYWJlbD5FLU1BSUw8L2xhYmVsPg0KCQkJPGlucHV0IGNsYXNzPSJmb3JtLWNvbnRyb2wiIG5hbWU9ImVtYWlsIiB2YWx1ZT0iIj4NCgkJPC9kaXY+DQoJCTxkaXYgY2xhc3M9ImZvcm0tZ3JvdXAiPg0KCQkJPGxhYmVsPtCf0LDRgNC+0LvRjDwvbGFiZWw+DQoJCQk8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgbmFtZT0icGFzc3dvcmQiIHZhbHVlPSIiPg0KCQk8L2Rpdj4NCgkJPGRpdiBjbGFzcz0iZm9ybS1ncm91cCI+DQoJCQk8bGFiZWw+0JPRgNCw0YTQsCAi0J7QsdC+INC80L3QtSI8L2xhYmVsPg0KCQkJPGlucHV0IGNsYXNzPSJmb3JtLWNvbnRyb2wiIG5hbWU9ImFib3V0IiB2YWx1ZT0iIj4NCgkJPC9kaXY+DQoJCTxidXR0b24gdHlwZT0ic3VibWl0IiBjbGFzcz0iYnRuIGJ0bi1kZWZhdWx0Ij7QodC+0LfQtNCw0YLRjCDQsNC60LrQsNGD0L3RgjwvYnV0dG9uPg0KCQk8L2Zvcm0+');
		break; 
    default:
        echo '<div class="jumbotron"><h2>Приветствуем вас!</h2><p>Данный мастер позволит вам установить игровой движок. Перед установкой убедитесь, что загрузили файлы-скрипты полностью.</p><p><a href="?step=1" class="btn btn-primary btn-lg" role="button">Проверка требований »</a></p></div>';
        break;
}
