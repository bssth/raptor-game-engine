<?php
	if(isset($_POST['new'])) 
	{
		Raptor::SetModConfig('inv_actions', array("mod" => "inv_actions", $_POST['name'] =>  array() ));
		echo '<div class="alert alert-success">'. Raptor::get_string('admin_saved') .'</div>';
	}
	if(isset($_GET['edit'])) 
	{
		if(isset($_POST['name'])) 
		{
			Raptor::SetModConfig('inv_actions', array("mod" => "inv_actions", array($_GET['edit'] =>  $_POST ) ));
			echo '<div class="alert alert-success">Действие <b>'. $_GET['edit'] .'</b> успешно отредактировано</div>';
		}
		$param = Raptor::ModConfig('inv_actions')[$_GET['edit']];
		echo '<form action="" method="POST">
		<div class="form-group"><label for="disabledSelect">'. Raptor::get_string('code') .'</label><input class="form-control" id="disabledInput" placeholder="'. $_GET['edit'] .'" disabled="" type="text"></div>
		<div class="form-group"><label>'. Raptor::get_string('available_cond') .'</label><input name="eval" value="'. $param['eval'] .'" class="form-control"><p class="help-block">Script with <b>return</b></div>
		<div class="form-group"><label>'. Raptor::get_string('title') .'</label><input name="name" value="'. $param['name'] .'" class="form-control"><p class="help-block">Название, отображаемое игрокам</p></div>
		<div class="form-group"><label>'. Raptor::get_string('name_en') .'</label><input name="name_en" value="'. $param['name_en'] .'" class="form-control"></div>
		<button type="submit" class="btn btn-default">'. Raptor::get_string('save') .'</button>
		</form>';
	}
	else 
	{
		echo '<div class="container-fluid">﻿<h2>'. Raptor::get_string('inv_acts') .'</h2>
		<br>
		<form method="POST">
		<p><input name="name" value="act_" type="text"></p>
		<p><button name="new" type="submit" value="1" class="btn btn-xs btn-default">'. Raptor::get_string('create') .'</button></p>
		</form>
		<hr><div class="table-responsive">
		<table class="table table-hover table-striped"><tbody>';
		foreach(Raptor::ModConfig('inv_actions') as $key => $value) 
		{
			if(!strstr($key, "act_")) 
			{ 
				continue; 
			}
			echo "<tr><td> <b><font size=3>". $value['name'] ."</font></b> </td> <td> <b><font size=3>". $key ."</font></b> </td> <td> <a href='?edit=". $key ."'>". Raptor::get_string('edit') ."</a> </td></tr>";
		}
		echo '</tbody></table>';
	}
?>