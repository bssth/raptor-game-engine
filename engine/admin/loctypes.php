<?php
	if(isset($_POST['new'])) 
	{
		Raptor::SetModConfig('location_types', array("mod" => "location_types", uniqid() =>  array('name' => $_POST['name']) ));
		echo '<div class="alert alert-success">'. Raptor::get_string('admin_saved') .'</div>';
	}
	if(isset($_GET['edit'])) 
	{
		if(isset($_POST['name'])) 
		{
			Raptor::SetModConfig('location_types', array($_GET['edit'] =>  $_POST ));
			echo '<div class="alert alert-success">'. Raptor::get_string('admin_saved') .'</div>';
		}
		$param = Raptor::ModConfig('location_types')[$_GET['edit']];
		echo '<form action="" method="POST">
		<div class="form-group"><label for="disabledSelect">ID</label><input class="form-control" id="disabledInput" placeholder="'. $_GET['edit'] .'" disabled="" type="text"></div>
		<div class="form-group"><label>'. Raptor::get_string('title') .'</label><input name="name" value="'. $param['name'] .'" class="form-control"><p class="help-block">Display name</p></div>
		<div class="form-group"><label>'. Raptor::get_string('name_en') .'</label><input name="name_en" value="'. $param['name_en'] .'" class="form-control"></div>
		<div class="form-group"><label>'. Raptor::get_string('module') .'</label>
		<select name="module" class="form-control">'; 
		foreach($GLOBALS['modules'] as $mod) 
		{
			echo '<option '. ($param['module']==$mod?'selected':'') .' value="'. $mod .'">'. $mod .'</option>';
		}
		echo '</select></div>
		<button type="submit" class="btn btn-default">'. Raptor::get_string('save') .'</button>
		</form>';
	}
	else 
	{
		echo '<div class="container-fluid">﻿<h2>Location types</h2>
			<h5></h5>
			<br>
			<form method="POST">
			<p><input name="name" value="" type="text"></p>
			<p><button name="new" type="submit" value="1" class="btn btn-xs btn-default">'. Raptor::get_string('create') .'</button></p>
			</form>
			<hr><div class="table-responsive">
			<table class="table table-hover table-striped"><tbody>';
		foreach(Raptor::ModConfig('location_types') as $key => $value) 
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