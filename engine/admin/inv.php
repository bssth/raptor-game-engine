﻿<?php
if (isset($_POST['new'])) 
{
    Database::Edit("config", array('mod' => 'inventory'), array('mod' => 'inventory', uniqid() => array('name' => $_POST['name'])));
    echo '<div class="alert alert-success">Предмет <b>' . $_POST['name'] . '</b> успешно создан</div>';
}
if (isset($_GET['edit'])) 
{
    if (isset($_POST['name'])) 
	{
        Database::Edit("config", array('mod' => 'inventory'), array($_GET['edit'] => $_POST));
        echo '<div class="alert alert-success">Предмет <b>' . $_GET['edit'] . '</b> успешно отредактирован</div>';
    }
    $param = Database::GetOne("config", array('mod' => 'inventory'))[$_GET['edit']];
    echo '<form action="" method="POST">
		<div class="form-group"><label for="disabledSelect">ID предмета</label><input class="form-control" id="disabledInput" placeholder="' . $_GET['edit'] . '" disabled="" type="text"></div>
		<div class="form-group"><label>Название предмета</label><input name="name" value="' . $param['name'] . '" class="form-control"><p class="help-block">Название, отображаемое игрокам</p></div>
		<div class="form-group"><label>Название предмета на английском</label><input name="name_en" value="' . $param['name_en'] . '" class="form-control"></div>
		<div class="form-group"><label>Изображение предмета (прямая ссылка)</label><input name="image" value="' . $param['image'] . '" class="form-control"></div>
		<div class="form-group"><label>Изображение предмета на игроке (прямая ссылка)</label><input name="equip_image" value="' . $param['equip_image'] . '" class="form-control"></div>
		<div class="form-group"><label>Стоимость параметра</label><input name="cost" value="' . $param['cost'] . '" class="form-control"> <select name="currency" class="form-control">';
    foreach (Database::GetOne("config", array("mod" => "currency")) as $key => $value) 
	{
        if (!strstr($key, "money_")) 
		{
            continue;
        }
        if (!is_array($value)) 
		{
            continue;
        }
        echo '<option ' . ($param['currency'] == $key ? 'selected' : '') . ' value="' . $key . '">' . $value['name'] . '</option>';
    }
    echo '</select></div>';
    foreach (Database::GetOne("config", array("mod" => "inv_params")) as $key => $value) 
	{
        if (!strstr($key, "p_") or ! is_array($value) or $value['type'] == 'script') 
		{
            continue;
        }
        echo '<div class="form-group"><label>' . $value['name'] . '</label><input name="' . $key . '" value="' . $param[$key] . '" class="form-control"></div>';
    }
    echo '<button type="submit" class="btn btn-default">Сохранить</button></form>';
} 
else 
{
    echo '<h2>Предметы</h2>
		<h5>Различные предметы инвентаря</h5>
		<br>
		<form method="POST">
		<p><input name="name" value="Наименование" type="text"></p>
		<p><button name="new" type="submit" value="1" class="btn btn-xs btn-default">Создать предмет</button></p>
		</form>
		<hr><div class="table-responsive"><table class="table table-bordered table-hover table-striped">
		<thead>
		<tr>
			<td>Название</td>
			<td>ID</td>
			<td></td>
		</tr>
		</thead>
		<tbody>';
    $inv = Database::GetOne("config", array('mod' => 'inventory'));
    foreach ($inv as $key => $value) 
	{
        if (!is_array($value)) 
		{
            continue;
        }
        echo "<tr><td> <b><font size=3>" . $value['name'] . "</font></b> </td> <td> <b><font size=3>" . $key . "</font></b> </td> <td> <a href='?edit=" . $key . "'>Редактировать</a> </td></tr>";
    }
    echo base64_decode('PC90Ym9keT4NCjwvdGFibGU+DQo8L2Rpdj4=');
}
?>