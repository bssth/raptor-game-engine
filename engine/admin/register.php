<?php 
	$ans = file_get_contents(RAPTOR_URL . "/api?a=check&url=". $GLOBALS['url']);

	if(strstr($ans, '"id"')) 
	{ 
		echo '<p>Игра зарегистрирована в каталоге RAPTOR. Подробные данные: <ul><samp>';
		foreach(json_decode($ans) as $key => $value)
		{
			echo '<p>'. $key .': <b>'. $value .'</b></p>';
		}
		echo '</samp></ul> Для отключения от аккаунта в системе RAPTOR требуется сделать это в личном кабинете на сайте '. RAPTOR_URL . '</p>';
	}
	else 
	{
		echo '<p>Игра не зарегистрирована в каталоге RAPTOR. Для регистрации требуется написать о вашем проекте на форуме RAPTOR Game Engine';
	}
