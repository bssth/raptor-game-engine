<h3>Полноценный интерфейс данной функции ещё не реализован до конца</h3>
Отправляем тестовый запрос на RAPTOR API... запрос: <?=RAPTOR_URL . "/api?a=test?";?>; ответ: <?=file_get_contents(RAPTOR_URL . "/api?a=test");?>
<hr>
<p>Проверяем наличие в базе RAPTOR (запрос: <?=RAPTOR_URL . "/api?a=check&url=". $GLOBALS['url'];?>)</p>
<?php 
	$ans = file_get_contents(RAPTOR_URL . "/api?a=check&url=". $GLOBALS['url']);
	if(isset($_GET['a'])) {
		$answer = file_get_contents(RAPTOR_URL . '/api?' . $_SERVER['QUERY_STRING']);
		echo "<p>Запрос: ". RAPTOR_URL . '/api?' . $_SERVER['QUERY_STRING'] .", ответ: " . $answer . "<br>" . (strstr($answer, '"id"') ? "<b>Игра зарегистрирована</b>" : "<b>Игра не зарегистрирована</b>") . "</p><hr>";
	}
	if(strstr($ans, '"id"')) { 
		echo '<p>Игра зарегистрирована в каталоге RAPTOR. Подробные данные: <p><samp>'. $ans .'</samp></p> Для отключения от аккаунта в системе RAPTOR требуется сделать это в личном кабинете на сайте '. RAPTOR_URL . '</p>';
	}
	else {
		echo '<p>Игра не зарегистрирована в каталоге RAPTOR. Для регистрации заполните форму:
		<form action="" method="GET">
			<p>
			<input type="hidden" name="name" value="'. $GLOBALS['name'] .'">
			<input type="hidden" name="id" value="'. $GLOBALS['id'] .'">
			<input type="hidden" name="private_key" value="'. $GLOBALS['private_key'] .'">
			<input type="hidden" name="public_key" value="'. $GLOBALS['public_key'] .'">
			<input type="hidden" name="url" value="'. $GLOBALS['url'] .'">
			<input type="hidden" name="a" value="addgame">
			<p><input type="text" name="owner" placeholder="Ваш логин в Block Studio"></p>
			<p><input type="text" name="logo" placeholder="Логотип игры (ссылка)"></p>
			<p><textarea name="desc" placeholder="Описание игры"></textarea></p>
			<p><input type="submit" value="Добавить в каталог"></p>
			</p>
		</form></p>';
	}
?>