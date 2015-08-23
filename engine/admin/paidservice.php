<?php
	if(isset($_POST['new'])) 
	{
		Database::Edit("config", array("mod" => "mod_paidservice"), array("mod" => "mod_paidservice", uniqid() =>  array('name' => $_POST['name']) ) );
		echo '<div class="alert alert-success">Платная услуга <b>'. $_POST['name'] .'</b> успешно создана</div>';
	}
	if(isset($_GET['edit'])) 
	{
		if(isset($_POST['name'])) 
		{
			Database::Edit("config", array("mod" => "mod_paidservice"), array($_GET['edit'] =>  $_POST ) );
			echo '<div class="alert alert-success">Платная услуга <b>'. $_GET['edit'] .'</b> успешно отредактирована</div>';
		}
		$param = Database::GetOne("config", array("mod" => "mod_paidservice"))[$_GET['edit']];
		echo '<form action="" method="POST">
		<div class="form-group"><label for="disabledSelect">Код услуги</label><input class="form-control" id="disabledInput" placeholder="'. $_GET['edit'] .'" disabled="" type="text"></div>
		<div class="form-group"><label>Название услуги</label><input name="name" value="'. $param['name'] .'" class="form-control"><p class="help-block">Название, отображаемое игрокам</p></div>
		<div class="form-group"><label>Название услуги на английском</label><input name="name_en" value="'. $param['name_en'] .'" class="form-control"></div>
		<div class="form-group"><label>Стоимость услуги</label><input name="cost" value="'. $param['cost'] .'" class="form-control"> <select name="currency" class="form-control">'; 
		foreach(Database::GetOne("config", array("mod" => "currency")) as $key => $value) 
		{
			if(!strstr($key, "money_")) 
			{ 
				continue; 
			}
			if(!is_array($value)) 
			{ 
				continue; 
			}
			echo '<option '. ($param['currency']==$key?'selected':'') .' value="'. $key .'">'. $value['name'] .'</option>';
		}
		echo '</select></div>
		<div class="form-group"><label>Время действия</label><input name="time" value="'. $param['time'] .'" class="form-control"></div>
		<div class="form-group">
            <label>Код, выполняемый во время покупки</label>
            <textarea name="eval_bought" class="form-control" rows="3">'. $param['eval_bought'] .'</textarea>
        </div>
		<div class="form-group">
            <label>Код, выполняемый по истечению времени</label>
            <textarea name="eval_expired" class="form-control" rows="3">'. $param['eval_expired'] .'</textarea>
        </div>
		<button type="submit" class="btn btn-default">Сохранить</button>
		</form>';
	}
	else 
	{
		echo '<div class="container-fluid">﻿<h2>Платные услуги</h2>
			<h5>Любые платные услуги, которые может приобрести персонаж за определённую сумму</h5>
			<br>
			<form method="POST">
			<p><input name="name" value="" type="text"></p>
			<p><button name="new" type="submit" value="1" class="btn btn-xs btn-default">Создать услугу</button></p>
			</form>
			<hr><div class="table-responsive">
			<table class="table table-hover table-striped"><tbody>';
			foreach(Database::GetOne("config", array("mod" => "mod_paidservice")) as $key => $value) 
			{
				if(!is_array($value)) 
				{ 
					continue; 
				}
				echo "<tr><td> <b><font size=3>". $value['name'] ."</font></b> </td> <td> <b><font size=3>". $key ."</font></b> </td> <td> <a href='?edit=". $key ."'>Редактировать</a> </td></tr>";
			}
		echo '</tbody></table>';;
	}
?>