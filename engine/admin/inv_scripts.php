<?php
	if(isset($_POST['new'])) 
	{
		Database::Edit("config", array("mod" => "inv_actions"), array("mod" => "inv_actions", $_POST['name'] =>  array() ) );
		echo '<div class="alert alert-success">Действие <b>'. $_POST['name'] .'</b> успешно создано</div>';
	}
	if(isset($_GET['edit'])) 
	{
		if(isset($_POST['name'])) 
		{
			Database::Edit("config", array("mod" => "inv_actions"), array($_GET['edit'] =>  $_POST ) );
			echo '<div class="alert alert-success">Действие <b>'. $_GET['edit'] .'</b> успешно отредактировано</div>';
		}
		$param = Database::GetOne("config", array("mod" => "inv_actions"))[$_GET['edit']];
		echo '<form action="" method="POST">
		<div class="form-group"><label for="disabledSelect">Код действия</label><input class="form-control" id="disabledInput" placeholder="'. $_GET['edit'] .'" disabled="" type="text"></div>
		<div class="form-group"><label>Условие доступности</label><input name="eval" value="'. $param['eval'] .'" class="form-control"><p class="help-block">Скриптовое условие; доступен объект <b>$char</b> и переменная <b>$id</b> с идентификатором предмета; используйте return</p></div>
		<div class="form-group"><label>Название действия</label><input name="name" value="'. $param['name'] .'" class="form-control"><p class="help-block">Название, отображаемое игрокам</p></div>
		<div class="form-group"><label>Название действия на английском</label><input name="name_en" value="'. $param['name_en'] .'" class="form-control"></div>
		<button type="submit" class="btn btn-default">Сохранить</button>
		</form>';
	}
	else 
	{
		echo '<div class="container-fluid">﻿<h2>Действия над предметами</h2>
		<h5>Здесь можно отредактировать действия, доступные для предметов</h5>
		<br>
		<form method="POST">
		<p><input name="name" value="act_" type="text"></p>
		<p><button name="new" type="submit" value="1" class="btn btn-xs btn-default">Создать действие</button></p>
		</form>
		<hr><div class="table-responsive">
		<table class="table table-hover table-striped"><tbody>';
		foreach(Database::GetOne("config", array("mod" => "inv_actions")) as $key => $value) 
		{
			if(!strstr($key, "act_")) 
			{ 
				continue; 
			}
			echo "<tr><td> <b><font size=3>". $value['name'] ."</font></b> </td> <td> <b><font size=3>". $key ."</font></b> </td> <td> <a href='?edit=". $key ."'>Редактировать</a> </td></tr>";
		}
		echo '</tbody></table>';
	}
?>