<?php
	/*
		** @last_edit 22.08.2015
		** @last_autor Mike
		** @todo Более защищённая и адекватная система
		** @comment Данная функция очень тестовая, ну очень. Делали для альфа-теста, пока поддержка этой функции не предоставляется (by Mike)
	*/
	
	if (isset($_POST['email'])) 
	{
			$mail = new Mail();
			if ($mail->sendMail(array('to' => $_POST['email'], 'subject' => $_POST['subject'], 'message' => $_POST['message']))) 
			{
				echo '<div class="alert alert-success">Сообщение отправлено</div>';
			} 
			else 
			{
				echo '<div class="alert alert-danger">Ошибка при отправке. Нажмите F5 для повторной попытки</div>';
			}
	}
	
	$time = time();
	$key = sha1($GLOBALS['private_key'] . $time);
	
	$default_subject = "Приглашение для регистрации в ". $GLOBALS['name'];
	$default_text = "Добрый день! Вы получили приглашение для регистрации в игре ". $GLOBALS['name'] .". \n\nСсылка для регистрации: http://". $GLOBALS['url'] ."/invite?t=". $time ."&key=". $key ." \n\n Желаем вам приятно провести время";

?>

<h3>Внимание! Данная функция является тестовой и мы не предоставляем по ней поддержку. Будьте осторожны!</h3>
<h4>Приватный ключ игры - <?=$GLOBALS['private_key'];?>. Текущее время по UNIX - <?=$time;?>. Исходя из этих данных, мы сгенерировали код приглашения: <?=$key;?>. Обновите страницу, чтобы получить новый ключ</h4>
<form method="POST" action="" role="form">
    <div class="form-group input-group">
        <span class="input-group-addon">@</span>
        <input class="form-control" name="email" placeholder="E-MAIL" type="email">
    </div>
    <div class="form-group">
        <label>Тема письма</label>
        <input class="form-control" value="<?=$default_subject;?>" name="subject" placeholder="Тема письма">
    </div>
    <div class="form-group">
        <label>Сообщение</label>
        <textarea class="form-control" name="message" rows="3"><?=$default_text;?></textarea>
    </div>
    <button type="submit" class="btn btn-default">Отправить сообщение</button>
</form>