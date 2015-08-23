<?php

/*
	@last_edit 22.08.2015
	@last_autor Mike
	@comment RCON-консоль. На данный момент на стадии тестирования
	@todo Безопасность
*/

@session_start();

	class rconDriver 
	{
		function actionIndex() 
		{
			if(strlen($GLOBALS['rcon']) <= 1) 
			{ 
				die("Техническая ошибка"); 
			}
			if(isset($_GET['rcon'])) 
			{
				$_SESSION["rcon"] = $_GET['rcon'];
				die("<h3>Обновите страницу, убрав <b>rcon=". $_GET['rcon'] ."</b></h3>");
			}
			elseif(@$_SESSION['rcon'] == $GLOBALS['rcon']) 
			{
				die("<h3>Парoль RCON верный. Можете переходить к консоли (/rcon/console или ?driver=rcon&action=console)</h3>");
			}
			else 
			{
				die("<h3>У вас нет RCON-прав. Для получения отправьте GET-запрос на эту страницу в виде: <b>?rcon=ПАРОЛЬ</b></h3>");
			}
		}
		function actionConsole() {
			if(strlen($GLOBALS['rcon']) <= 1) 
			{ 
				die("Техническая ошибка"); 
			}
			if(@$_SESSION['rcon'] != $GLOBALS['rcon']) 
			{ 
				die("<h3>Неверный RCON-пароль</h3>"); 
			}
			if(isset($_POST['code'])) 
			{
				echo '<div class="well">'. eval($_POST['code']) .'</div>';
			}
			raptor_print('PGZvcm0gYWN0aW9uPSIiIG1ldGhvZD0iUE9TVCI+PHRleHRhcmVhIGNsYXNzPSJmb3JtLWNvbnRyb2wiIHJvd3M9IjMiIG5hbWU9ImNvZGUiPjwvdGV4dGFyZWE+PGlucHV0IGNsYXNzPSJidG4gYnRuLWRlZmF1bHQiIHR5cGU9InN1Ym1pdCIgdmFsdWU9ItCX0LDQv9GD0YHRgtC40YLRjCI+PC9mb3JtPg==');
		}
	}
?>
