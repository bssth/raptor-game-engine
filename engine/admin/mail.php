<?php
/**
	** @todo Оптимизировать весь этот бардак
	** @last_edit 22.08.2015 by Mike
*/

switch(@$_GET['a']) 
{
	case "single":
		if (isset($_POST['email'])) 
		{
			$mail = new Mail();
			if ($mail->sendMail(array('to' => $_POST['email'], 'subject' => $_POST['subject'], 'message' => $_POST['message']))) 
			{
				echo '<div class="alert alert-success">'. Raptor::get_string('admin_saved') .'</div>';
			} 
			else 
			{
				echo '<div class="alert alert-danger">'. Raptor::get_string('error') .'</div>';
			}
		}
		print('<form method="POST" action="" role="form">
		<div class="form-group input-group">
			<span class="input-group-addon">@</span>
			<input class="form-control" name="email" placeholder="E-MAIL" type="email">
		</div>
		<div class="form-group">
			<label>'. Raptor::get_string('title') .'</label>
			<input class="form-control" name="subject" placeholder="Subject">
		</div>
		<div class="form-group">
			<label>'. Raptor::get_string('message') .'</label>
			<textarea class="form-control" name="message" rows="3"></textarea>
		</div>
		<button type="submit" class="btn btn-default">'. Raptor::get_string('send') .'</button>
	</form>');
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
					echo '<div class="alert alert-danger">'. Raptor::get_string('error') .'; <b>'. $a['email'] .'</b> ('. $a['login'] .')</div>';
				}
			}
			echo '<div class="alert alert-success">'. Raptor::get_string('send') .' (<b>'. $count .'</b> '. Raptor::get_string('count_m') .')</div>';
		}
		echo '
		<form method="POST" action="" role="form">
			<div class="form-group">
				<label>'. Raptor::get_string('title') .'</label>
				<input class="form-control" name="subject" placeholder="Subject">
			</div>
			<div class="form-group">
				<label>'. Raptor::get_string('message') .'</label>
				<textarea class="form-control" name="message" rows="3"></textarea>
			</div>
			<button type="submit" class="btn btn-default">'. Raptor::get_string('send') .'</button>
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
					echo '<div class="alert alert-danger">'. Raptor::get_string('error') .'; <b>'. $a['email'] .'</b> ('. $a['login'] .')</div>';
				}
				unset($player);
			}
			echo '<div class="alert alert-success">'. Raptor::get_string('send') .' (<b>'. $count .' '. Raptor::get_string('count_m') .'</b>)</div>';
		}
		echo '
		<form method="POST" action="" role="form">
			<div class="form-group">
				<label>'. Raptor::get_string('title') .'</label>
				<input class="form-control" name="subject" placeholder="Subject">
			</div>
			<div class="form-group">
				<label>'. Raptor::get_string('message') .'</label>
				<textarea class="form-control" name="message" rows="3"></textarea>
			</div>
			<div class="form-group">
				<label>'. Raptor::get_string('script') .'</label>
				<input class="form-control" name="eval" placeholder="When should I send message?">
				<p class="help-block">'. Raptor::get_string('mail_tip') .'</p>
			</div> 
			<button type="submit" class="btn btn-default">'. Raptor::get_string('send') .'</button>
		</form>';
		break;
	default:
		print('<div class="container"><div class="navbar-header"><button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a class="navbar-brand" href="#">Рассылки</a></div><div class="navbar-collapse collapse"><ul class="nav navbar-nav"><li><a href="?a=single">1 address</a></li><li><a href="?a=massive">all adresses</a></li><li><a href="?a=masscript">specified  addresses</a></li></ul></div></div>');
		break;
}
?>