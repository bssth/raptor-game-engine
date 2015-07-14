<?php
	if(isset($_POST['new'])) {
		Database::Edit("config", array("mod" => "location_types"), array("mod" => "location_types", uniqid() =>  array('name' => $_POST['name']) ) );
		echo '<div class="alert alert-success">Тип локаций <b>'. $_POST['name'] .'</b> успешно создан</div>';
	}
	if(isset($_GET['edit'])) {
		if(isset($_POST['name'])) {
			Database::Edit("config", array("mod" => "location_types"), array($_GET['edit'] =>  $_POST ) );
			echo '<div class="alert alert-success">Тип локаций <b>'. $_GET['edit'] .'</b> успешно отредактирован</div>';
		}
		$param = Database::GetOne("config", array("mod" => "location_types"))[$_GET['edit']];
		echo '<form action="" method="POST">
		<div class="form-group"><label for="disabledSelect">ID типа</label><input class="form-control" id="disabledInput" placeholder="'. $_GET['edit'] .'" disabled="" type="text"></div>
		<div class="form-group"><label>Название типа</label><input name="name" value="'. $param['name'] .'" class="form-control"><p class="help-block">Название, отображаемое игрокам</p></div>
		<div class="form-group"><label>Название услуги на английском</label><input name="name_en" value="'. $param['name_en'] .'" class="form-control"></div>
		<div class="form-group"><label>Модуль системы</label>
		<select name="module" class="form-control">'; 
		foreach($GLOBALS['modules'] as $mod) {
			echo '<option '. ($param['module']==$mod?'selected':'') .' value="'. $mod .'">'. $mod .'</option>';
		}
		echo '</select></div>
		<button type="submit" class="btn btn-default">Сохранить</button>
		</form>';
	}
	else {
		echo '<div class="container-fluid">﻿<h2>Типы локаций</h2>
			<h5>Здесь можно настраивать типы локаций (например: текстовые, RPG.JS, Canvas, Unity, Flash). Для нормальной работы требуется отдельный модуль.</h5>
			<br>
			<form method="POST">
			<p><input name="name" value="" type="text"></p>
			<p><button name="new" type="submit" value="1" class="btn btn-xs btn-default">Создать тип</button></p>
			</form>
			<hr><div class="table-responsive">
			<table class="table table-hover table-striped"><tbody>';
		foreach(Database::GetOne("config", array("mod" => "location_types")) as $key => $value) {
			if(!is_array($value)) { continue; }
			echo "<tr><td> <b><font size=3>". $value['name'] ."</font></b> </td> <td> <b><font size=3>". $key ."</font></b> </td> <td> <a href='?edit=". $key ."'>Редактировать</a> </td></tr>";
		}
		echo '</tbody></table>';;
	}
?>