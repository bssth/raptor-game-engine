<?php
	if(isset($_POST['new'])) {
		Database::Edit("config", array("mod" => "locations"), array(uniqid() =>  array('name' => $_POST['name']) ) );
		echo '<div class="alert alert-success">Локация <b>'. $_POST['name'] .'</b> успешно создана</div>';
	}
	if(isset($_GET['edit'])) {
		if(isset($_POST['name'])) {
			Database::Edit("config", array("mod" => "locations"), array($_GET['edit'] =>  $_POST ) );
			echo '<div class="alert alert-success">Локация <b>'. $_GET['edit'] .'</b> успешно отредактирована</div>';
		}
		$param = Database::GetOne("config", array("mod" => "locations"))[$_GET['edit']];
		echo '<form action="" method="POST">
		<div class="form-group"><label for="disabledSelect">Код локации</label><input class="form-control" id="disabledInput" placeholder="'. $_GET['edit'] .'" disabled="" type="text"></div>
		<div class="form-group"><label>Название локации</label><input name="name" value="'. $param['name'] .'" class="form-control"><p class="help-block">Название, отображаемое игрокам</p></div>
		<div class="form-group"><label>Название локации на английском</label><input name="name_en" value="'. $param['name_en'] .'" class="form-control"></div>
		<div class="form-group"><label>Номер JSON-карты</label><input name="map" value="'. $param['map'] .'" class="form-control"></div>
		<button type="submit" class="btn btn-default">Сохранить</button>
		</form>';
	}
	else {
		echo '<div class="container-fluid">﻿<h2>Локации</h2>
			<h5>Игровые локации. Не путайте их с картами. Локация - это игровая структура, которая включает в себя карту, объекты, игроков на ней и различные спецфункции (магазины и т.п.). Карта - это описание внешнего вида локации: где размещены какие объекты, тайлы и т.д.</h5>
			<br>
			<form method="POST">
			<p><input name="name" value="" type="text"></p>
			<p><button name="new" type="submit" value="1" class="btn btn-xs btn-default">Создать локацию</button></p>
			</form>
			<hr><div class="table-responsive">
			<table class="table table-hover table-striped"><tbody>';
		foreach(Database::GetOne("config", array("mod" => "locations")) as $key => $value) {
			if(!is_array($value)) { continue; }
			echo "<tr><td> <b><font size=3>". $value['name'] ."</font></b> </td> <td> <b><font size=3>". $key ."</font></b> </td> <td> <a href='?edit=". $key ."'>Редактировать</a> </td></tr>";
		}
		echo '</tbody></table>';;
	}
?>