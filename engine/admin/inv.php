<?php
if (isset($_POST['new'])) 
{
    Raptor::SetModConfig('inventory', array('mod' => 'inventory', uniqid() => array('name' => $_POST['name'])));
    echo '<div class="alert alert-success">'. Raptor::get_string('admin_saved') .'</div>';
}
if (isset($_GET['edit'])) 
{
    if (isset($_POST['name'])) 
	{
		Raptor::SetModConfig('inventory', array($_GET['edit'] => $_POST));
        echo '<div class="alert alert-success">'. Raptor::get_string('admin_saved') .'</div>';
    }
    $param = Raptor::ModConfig('inventory')[$_GET['edit']];
    echo '<form action="" method="POST">
		<div class="form-group"><label for="disabledSelect">ID</label><input class="form-control" id="disabledInput" placeholder="' . $_GET['edit'] . '" disabled="" type="text"></div>
		<div class="form-group"><label>'. Raptor::get_string('name') .'</label><input name="name" value="' . $param['name'] . '" class="form-control"><p class="help-block">Название, отображаемое игрокам</p></div>
		<div class="form-group"><label>'. Raptor::get_string('name_en') .'</label><input name="name_en" value="' . $param['name_en'] . '" class="form-control"></div>
		<div class="form-group"><label>'. Raptor::get_string('img') .'</label><input name="image" value="' . $param['image'] . '" class="form-control"></div>
		<div class="form-group"><label>'. Raptor::get_string('img_on_pl') .'</label><input name="equip_image" value="' . $param['equip_image'] . '" class="form-control"></div>
		<div class="form-group"><label>'. Raptor::get_string('cost') .'</label><input name="cost" value="' . $param['cost'] . '" class="form-control"> <select name="currency" class="form-control">';
    foreach (Raptor::ModConfig('currency') as $key => $value) 
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
    foreach (Raptor::ModConfig('inv_params') as $key => $value) 
	{
        if (!strstr($key, "p_") or ! is_array($value) or $value['type'] == 'script') 
		{
            continue;
        }
        echo '<div class="form-group"><label>' . $value['name'] . '</label><input name="' . $key . '" value="' . $param[$key] . '" class="form-control"></div>';
    }
    echo '<button type="submit" class="btn btn-default">'. Raptor::get_string('save') .'</button></form>';
} 
else 
{
    echo '<h2>'. Raptor::get_string('items') .'</h2>
		<br>
		<form method="POST">
		<p><input name="name" value="'. Raptor::get_string('name') .'" type="text"></p>
		<p><button name="new" type="submit" value="1" class="btn btn-xs btn-default">'. Raptor::get_string('create') .'</button></p>
		</form>
		<hr><div class="table-responsive"><table class="table table-bordered table-hover table-striped">
		<thead>
		<tr>
			<td>'. Raptor::get_string('name') .'</td>
			<td>ID</td>
			<td></td>
		</tr>
		</thead>
		<tbody>';
    $inv = Raptor::ModConfig('inventory');
    foreach ($inv as $key => $value) 
	{
        if (!is_array($value)) 
		{
            continue;
        }
        echo "<tr><td> <b><font size=3>" . $value['name'] . "</font></b> </td> <td> <b><font size=3>" . $key . "</font></b> </td> <td> <a href='?edit=" . $key . "'>". Raptor::get_string('edit') ."</a> </td></tr>";
    }
    echo base64_decode('PC90Ym9keT4NCjwvdGFibGU+DQo8L2Rpdj4=');
}
?>