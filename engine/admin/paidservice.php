<?php
	if(isset($_POST['new'])) 
	{
		Raptor::SetModConfig('mod_paidservice', array("mod" => "mod_paidservice", uniqid() =>  array('name' => $_POST['name']) ));
		echo '<div class="alert alert-success">'. Raptor::get_string('admin_saved') .'</div>';
	}
	if(isset($_GET['edit'])) 
	{
		if(isset($_POST['name'])) 
		{
			Raptor::SetModConfig('mod_paidservice', array($_GET['edit'] =>  $_POST ));
			echo '<div class="alert alert-success">'. Raptor::get_string('admin_saved') .'</div>';
		}
		$param = Raptor::ModConfig('mod_paidservice')[$_GET['edit']];
		echo '<form action="" method="POST">
		<div class="form-group"><label for="disabledSelect">'. Raptor::get_string('code') .'</label><input class="form-control" id="disabledInput" placeholder="'. $_GET['edit'] .'" disabled="" type="text"></div>
		<div class="form-group"><label>'. Raptor::get_string('name') .'</label><input name="name" value="'. $param['name'] .'" class="form-control"><p class="help-block">Название, отображаемое игрокам</p></div>
		<div class="form-group"><label>'. Raptor::get_string('name_en') .'</label><input name="name_en" value="'. $param['name_en'] .'" class="form-control"></div>
		<div class="form-group"><label>'. Raptor::get_string('cost') .'</label><input name="cost" value="'. $param['cost'] .'" class="form-control"> <select name="currency" class="form-control">'; 
		foreach(Raptor::ModConfig('currency') as $key => $value) 
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
		<div class="form-group"><label>'. Raptor::get_string('time') .'</label><input name="time" value="'. $param['time'] .'" class="form-control"></div>
		<div class="form-group">
            <label>'. Raptor::get_string('code_in') .'</label>
            <textarea name="eval_bought" class="form-control" rows="3">'. $param['eval_bought'] .'</textarea>
        </div>
		<div class="form-group">
            <label>'. Raptor::get_string('code_af') .'</label>
            <textarea name="eval_expired" class="form-control" rows="3">'. $param['eval_expired'] .'</textarea>
        </div>
		<button type="submit" class="btn btn-default">'. Raptor::get_string('save') .'</button>
		</form>';
	}
	else 
	{
		echo '<div class="container-fluid">﻿<h2>'. Raptor::get_string('paidservices') .'</h2>
			<br>
			<form method="POST">
			<p><input name="name" value="" type="text"></p>
			<p><button name="new" type="submit" value="1" class="btn btn-xs btn-default">'. Raptor::get_string('create') .'</button></p>
			</form>
			<hr><div class="table-responsive">
			<table class="table table-hover table-striped"><tbody>';
			foreach(Raptor::ModConfig('mod_paidservice') as $key => $value) 
			{
				if(!is_array($value)) 
				{ 
					continue; 
				}
				echo "<tr><td> <b><font size=3>". $value['name'] ."</font></b> </td> <td> <b><font size=3>". $key ."</font></b> </td> <td> <a href='?edit=". $key ."'>". Raptor::get_string('edit') ."</a> </td></tr>";
			}
		echo '</tbody></table>';
	}
?>