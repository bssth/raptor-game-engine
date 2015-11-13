<?php
	if(isset($_POST['new'])) 
	{
		Raptor::SetModConfig('locations', array(uniqid() =>  array('name' => $_POST['name']) ));
		echo '<div class="alert alert-success">'. Raptor::get_string('admin_saved') .'</div>';
	}
	if(isset($_GET['edit'])) 
	{
		if(isset($_POST['name'])) 
		{
			Raptor::SetModConfig('locations', array($_GET['edit'] =>  $_POST ));
			echo '<div class="alert alert-success">'. Raptor::get_string('admin_saved') .'</div>';
		}
		$param = Raptor::ModConfig('locations')[$_GET['edit']];
		echo '<form action="" method="POST">
		<div class="form-group"><label for="disabledSelect">'. Raptor::get_string('code') .'</label><input class="form-control" id="disabledInput" placeholder="'. $_GET['edit'] .'" disabled="" type="text"></div>
		<div class="form-group"><label>'. Raptor::get_string('title') .'</label><input name="name" value="'. $param['name'] .'" class="form-control"><p class="help-block"></p></div>
		<div class="form-group"><label>'. Raptor::get_string('name_en') .'</label><input name="name_en" value="'. $param['name_en'] .'" class="form-control"></div>
		<div class="form-group"><label>'. Raptor::get_string('type') .'</label>
		<select name="type" class="form-control">
		<option '. (!$param['type'] or $param['type']=='default'?'selected':'') .' value="default">-</option>'; 
		foreach(Raptor::ModConfig('location_types') as $key => $value) 
		{
			if(!is_array($value)) { continue; }
			echo '<option '. ($param['type']==$key?'selected':'') .' value="'. $key .'">'. $value['name'] .'</option>';
		}
		echo '</select></div>
		<div class="form-group"><label>'. ((!$param['type'] or $param['type']=='default') ? Raptor::get_string('mapjson') : Raptor::get_string('params')) .'</label><input name="map" value="'. $param['map'] .'" class="form-control"></div>
		<button type="submit" class="btn btn-default">'. Raptor::get_string('save') .'</button>
		</form>';
	}
	else 
	{
		echo '<div class="container-fluid">﻿<h2>'. Raptor::get_string('locations') .'</h2>
			<br>
			<form method="POST">
			<p><input name="name" value="" type="text"></p>
			<p><button name="new" type="submit" value="1" class="btn btn-xs btn-default">'. Raptor::get_string('create') .'</button></p>
			</form>
			<hr><div class="table-responsive">
			<table class="table table-hover table-striped"><tbody>';
		foreach(Raptor::ModConfig('locations') as $key => $value) 
		{
			if(!is_array($value)) 
			{ 
				continue; 
			}
			echo "<tr><td> <b><font size=3>". $value['name'] ."</font></b> </td> <td> <b><font size=3>". $key ."</font></b> </td> <td> <a href='?edit=". $key ."'>". Raptor::get_string('edit') ."</a> </td></tr>";
		}
		echo '</tbody></table>';;
	}
?>