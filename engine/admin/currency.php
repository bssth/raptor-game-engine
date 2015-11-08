<?php
if (isset($_POST['new'])) 
{
	Raptor::SetModConfig('currency', array($_POST['name'] => array()));
    echo '<div class="alert alert-success">'. Raptor::get_string('admin_saved') .'</div>';
}
if (isset($_GET['edit'])) 
{
    if (isset($_POST['name'])) 
	{
		Raptor::SetModConfig('currency', array($_GET['edit'] => $_POST));
        echo '<div class="alert alert-success">'. Raptor::get_string('admin_saved') .'</div>';
    }
    $param = Raptor::ModConfig('currency')[$_GET['edit']];
    echo '<form action="" method="POST">
		<div class="form-group"><label for="disabledSelect">'. Raptor::get_string('code') .'</label><input class="form-control" id="disabledInput" placeholder="' . $_GET['edit'] . '" disabled="" type="text"></div>
		<div class="form-group"><label>'. Raptor::get_string('name') .'</label><input name="name" value="' . $param['name'] . '" class="form-control"><p class="help-block">Название, отображаемое игрокам</p></div>
		<div class="form-group"><label>'. Raptor::get_string('name_en') .'</label><input name="name_en" value="' . $param['name_en'] . '" class="form-control"></div>
		<div class="form-group"><label>'. Raptor::get_string('icon') .'</label><input id="c_img" name="img" value="' . $param['img'] . '" class="form-control"></div>
		<div class="form-group"></div>
		<button type="submit" class="btn btn-default">'. Raptor::get_string('save') .'</button>
		</form>';
} 
else 
{
    echo '<h2>'. Raptor::get_string('currencies') .'</h2>
		<br>
		<form method="POST">
		<p><input name="name" value="money_" type="text"></p>
		<p><button name="new" type="submit" value="1" class="btn btn-xs btn-default">'. Raptor::get_string('create') .'</button></p>
		</form>
		<hr>
		<div class="table-responsive">
		<hr><table class="table table-bordered table-hover table-striped">
		<thead>
		<tr>
			<td>'. Raptor::get_string('name') .'</td>
			<td>'. Raptor::get_string('code') .'</td>
			<td></td>
		</tr>
		</thead>
		<tbody>';
    foreach (Raptor::ModConfig('currency') as $key => $value) 
	{
        if (!strstr($key, "money_")) 
		{
            continue;
        }
        echo "<tr><td> <b><font size=3>" . $value['name'] . "</font></b> </td> <td> <b><font size=3>" . $key . "</font></b> </td> <td> <a href='?edit=" . $key . "'>". Raptor::get_string('edit') ."</a> </td></tr>";
    }
    echo '</tbody>
		</table>
		</div>';
}
