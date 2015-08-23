<?php
/**
	** @todo Оптимизировать весь этот бардак
	** @last_edit 22.08.2015
	** @last_autor Mike
*/

switch(@$_GET['a']) 
{
	case "single":
		if (isset($_POST['email'])) 
		{
			$mail = new Mail();
			if ($mail->sendMail(array('to' => $_POST['email'], 'subject' => $_POST['subject'], 'message' => $_POST['message']))) 
			{
				echo '<div class="alert alert-success">Сообщение отправлено</div>';
			} 
			else 
			{
				echo '<div class="alert alert-danger">Ошибка при отправке</div>';
			}
		}
		raptor_print('PGZvcm0gbWV0aG9kPSJQT1NUIiBhY3Rpb249IiIgcm9sZT0iZm9ybSI+DQogICAgPGRpdiBjbGFzcz0iZm9ybS1ncm91cCBpbnB1dC1ncm91cCI+DQogICAgICAgIDxzcGFuIGNsYXNzPSJpbnB1dC1ncm91cC1hZGRvbiI+QDwvc3Bhbj4NCiAgICAgICAgPGlucHV0IGNsYXNzPSJmb3JtLWNvbnRyb2wiIG5hbWU9ImVtYWlsIiBwbGFjZWhvbGRlcj0iRS1NQUlMIiB0eXBlPSJlbWFpbCI+DQogICAgPC9kaXY+DQogICAgPGRpdiBjbGFzcz0iZm9ybS1ncm91cCI+DQogICAgICAgIDxsYWJlbD7QotC10LzQsCDQv9C40YHRjNC80LA8L2xhYmVsPg0KICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgbmFtZT0ic3ViamVjdCIgcGxhY2Vob2xkZXI9ItCi0LXQvNCwINC/0LjRgdGM0LzQsCI+DQogICAgPC9kaXY+DQogICAgPGRpdiBjbGFzcz0iZm9ybS1ncm91cCI+DQogICAgICAgIDxsYWJlbD7QodC+0L7QsdGJ0LXQvdC40LU8L2xhYmVsPg0KICAgICAgICA8dGV4dGFyZWEgY2xhc3M9ImZvcm0tY29udHJvbCIgbmFtZT0ibWVzc2FnZSIgcm93cz0iMyI+PC90ZXh0YXJlYT4NCiAgICA8L2Rpdj4NCiAgICA8YnV0dG9uIHR5cGU9InN1Ym1pdCIgY2xhc3M9ImJ0biBidG4tZGVmYXVsdCI+0J7RgtC/0YDQsNCy0LjRgtGMINGB0L7QvtCx0YnQtdC90LjQtTwvYnV0dG9uPg0KPC9mb3JtPg==');
		break;
	case "massive":
		if (isset($_POST['subject'])) 
		{
			$mail = new Mail();
			$players = Database::GetAll('players');
			foreach($players as $a) 
			{
				if ($mail->sendMail(array('to' => $a['email'], 'subject' => $_POST['subject'], 'message' => $_POST['message']))) 
				{
					$count += 1;
				} 
				else 
				{
					echo '<div class="alert alert-danger">Ошибка при отправке на <b>'. $a['email'] .'</b> ('. $a['login'] .')</div>';
				}
			}
			echo '<div class="alert alert-success">Сообщение отправлено на <b>'. $count .'</b> адресов</div>';
		}
		echo '
		<form method="POST" action="" role="form">
			<div class="form-group">
				<label>Тема письма</label>
				<input class="form-control" name="subject" placeholder="Тема письма">
			</div>
			<div class="form-group">
				<label>Сообщение</label>
				<textarea class="form-control" name="message" rows="3"></textarea>
			</div>
			<button type="submit" class="btn btn-default">Отправить сообщение</button>
		</form>';
		break;
	case "masscript":
		if (isset($_POST['eval'])) 
		{
			$mail = new Mail();
			$players = Database::GetAll('players');
			foreach($players as $a) 
			{
				$player = new Player(__toString($a['_id']));
				if(!eval($_POST['eval'])) 
				{ 
					unset($player); 
					continue; 
				}
				if ($mail->sendMail(array('to' => $a['email'], 'subject' => $_POST['subject'], 'message' => $_POST['message']))) 
				{
					$count += 1;
				} 
				else 
				{
					echo '<div class="alert alert-danger">Ошибка при отправке на <b>'. $a['email'] .'</b> ('. $a['login'] .')</div>';
				}
				unset($player);
			}
			echo '<div class="alert alert-success">Сообщение отправлено на <b>'. $count .'</b> адресов</div>';
		}
		echo '
		<form method="POST" action="" role="form">
			<div class="form-group">
				<label>Тема письма</label>
				<input class="form-control" name="subject" placeholder="Тема письма">
			</div>
			<div class="form-group">
				<label>Сообщение</label>
				<textarea class="form-control" name="message" rows="3"></textarea>
			</div>
			<div class="form-group">
				<label>Скриптовое условие (проверяется для каждого игрока отдельно)</label>
				<input class="form-control" name="eval" placeholder="Условие">
				<p class="help-block">Если вернётся true или аналог, сообщение будет отправлено; доступен объект <b>$player</b> с экземпляром класса Player. Пример использования: <b>return strstr($player->email, "@gmail.com");</b> - здесь письмо будет отправлено лишь владельцам почты на GMail</p>
			</div>
			<button type="submit" class="btn btn-default">Отправить сообщение</button>
		</form>';
		break;
	default:
		raptor_print('PGRpdiBjbGFzcz0iY29udGFpbmVyIj48ZGl2IGNsYXNzPSJuYXZiYXItaGVhZGVyIj48YnV0dG9uIHR5cGU9ImJ1dHRvbiIgY2xhc3M9Im5hdmJhci10b2dnbGUiIGRhdGEtdG9nZ2xlPSJjb2xsYXBzZSIgZGF0YS10YXJnZXQ9Ii5uYXZiYXItY29sbGFwc2UiPjxzcGFuIGNsYXNzPSJzci1vbmx5Ij5Ub2dnbGUgbmF2aWdhdGlvbjwvc3Bhbj48c3BhbiBjbGFzcz0iaWNvbi1iYXIiPjwvc3Bhbj48c3BhbiBjbGFzcz0iaWNvbi1iYXIiPjwvc3Bhbj48c3BhbiBjbGFzcz0iaWNvbi1iYXIiPjwvc3Bhbj48L2J1dHRvbj48YSBjbGFzcz0ibmF2YmFyLWJyYW5kIiBocmVmPSIjIj7QoNCw0YHRgdGL0LvQutC4PC9hPjwvZGl2PjxkaXYgY2xhc3M9Im5hdmJhci1jb2xsYXBzZSBjb2xsYXBzZSI+PHVsIGNsYXNzPSJuYXYgbmF2YmFyLW5hdiI+PGxpPjxhIGhyZWY9Ij9hPXNpbmdsZSI+0J7RgtC/0YDQsNCy0LrQsCDQvdCwINCw0LTRgNC10YE8L2E+PC9saT48bGk+PGEgaHJlZj0iP2E9bWFzc2l2ZSI+0JzQsNGB0YHQvtCy0LDRjyDQvtGC0L/RgNCw0LLQutCwPC9hPjwvbGk+PGxpPjxhIGhyZWY9Ij9hPW1hc3NjcmlwdCI+0JzQsNGB0YHQvtCy0LDRjyDRgdC+INGB0LrRgNC40L/RgtC+0LLRi9C8INGD0YHQu9C+0LLQuNC10Lw8L2E+PC9saT48L3VsPjwvZGl2PjwvZGl2Pg==');
		break;
}
?>