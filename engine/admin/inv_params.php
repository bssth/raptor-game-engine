<?php
if (isset($_POST['new'])) 
{
	Raptor::SetModConfig('inv_params', array("mod" => "inv_params", $_POST['name'] => array()));
    echo '<div class="alert alert-success">'. Raptor::get_string('admin_saved') .'</div>';
}
if (isset($_GET['edit'])) 
{
    if (isset($_POST['name'])) 
	{
		Raptor::SetModConfig('inv_params', array($_GET['edit'] => $_POST));
        echo '<div class="alert alert-success">'. Raptor::get_string('admin_saved') .'</div>';
    }
    $param = Raptor::ModConfig('inv_params')[$_GET['edit']];
    echo '<form action="" method="POST">
		<div class="form-group"><label for="disabledSelect">'. Raptor::get_string('code') .'</label><input class="form-control" id="disabledInput" placeholder="' . $_GET['edit'] . '" disabled="" type="text"></div>
		<div class="form-group"><label>'. Raptor::get_string('name') .'</label><input name="name" value="' . $param['name'] . '" class="form-control"><p class="help-block">Название, отображаемое игрокам</p></div>
		<div class="form-group"><label>'. Raptor::get_string('name_en') .'</label><input name="name_en" value="' . $param['name_en'] . '" class="form-control"></div>
		<div class="form-group"><label>'. Raptor::get_string('type') .'</label><select name="type" onchange="if (this.selectedIndex == 3) document.getElementById(\'script_text\').style.display = \'block\'" class="form-control"><option value="int" ' . ($param['type'] == 'int' ? 'selected' : '') . '>'. Raptor::get_string('int') .'</option><option value="float" ' . ($param['type'] == 'float' ? 'selected' : '') . '>'. Raptor::get_string('float') .'</option><option ' . ($param['type'] == 'str' ? 'selected' : '') . ' value="str">'. Raptor::get_string('string') .'</option><option ' . ($param['type'] == 'id' ? 'selected' : '') . ' value="id">ID </option><option ' . ($param['type'] == 'script' ? 'selected' : '') . ' value="script" >'. Raptor::get_string('script') .'</option></select></div>
		<div class="form-group" style="display: ' . ($param['type'] == 'script' ? 'block' : 'none') . ';" id="script_text"><label>'. Raptor::get_string('script') .'</label><input name="script" value="' . $param['script'] . '" class="form-control"></div>
		<button type="submit" class="btn btn-default">'. Raptor::get_string('save') .'</button>
		</form>';
} 
else 
{
    echo '<div class="container-fluid">﻿<h2>'. Raptor::get_string('inv_params') .'</h2>
	<br>
	<form method="POST">
	<p><input name="name" value="p_" type="text"></p>
	<p><button name="new" type="submit" value="1" class="btn btn-xs btn-default">'. Raptor::get_string('create') .'</button></p>
	</form>
	<hr><div class="table-responsive">
	<table class="table table-hover table-striped"><tbody>';
    foreach (Raptor::ModConfig('inv_params') as $key => $value) 
	{
        if (!strstr($key, "p_")) 
		{
            continue;
        }
        echo "<tr><td> <b><font size=3>" . $value['name'] . "</font></b> </td> <td> <b><font size=3>" . $key . "</font></b> </td> <td> <a href='?edit=" . $key . "'>". Raptor::get_string('edit') ."</a> </td></tr>";
    }
    echo '</tbody></table>';
}
?>