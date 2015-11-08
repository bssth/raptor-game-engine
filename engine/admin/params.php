<?php
if (isset($_POST['new'])) 
{
	Raptor::SetModConfig('params', array("mod" => "params", $_POST['name'] => array()));
    echo '<div class="alert alert-success">' . $_POST['name'] . ' '. Raptor::get_string('edited', true) .'</div>';
}
if (isset($_GET['edit'])) 
{
    if (isset($_POST['name'])) 
	{
		Raptor::SetModConfig('params', array("mod" => "params", $_GET['edit'] => $_POST));
        echo '<div class="alert alert-success"><b>' . $_GET['edit'] . '</b> '. Raptor::get_string('edited', true) .'</div>';
    }
    $param = Raptor::ModConfig('params')[$_GET['edit']];
    echo '<form action="" method="POST">
		<div class="form-group"><label for="disabledSelect">'. Raptor::get_string('code') .'</label><input class="form-control" id="disabledInput" placeholder="' . $_GET['edit'] . '" disabled="" type="text"></div>
		<div class="form-group"><label>'. Raptor::get_string('name') .'</label><input name="name" value="' . $param['name'] . '" class="form-control"><p class="help-block">Название, отображаемое игрокам</p></div>
		<div class="form-group"><label>'. Raptor::get_string('name_en') .'</label><input name="name_en" value="' . $param['name_en'] . '" class="form-control"></div>
		<div class="form-group"><label>'. Raptor::get_string('type') .'</label><select name="type" onchange="if (this.selectedIndex == 3) document.getElementById(\'script_text\').style.display = \'block\'" class="form-control"><option value="int" ' . ($param['type'] == 'int' ? 'selected' : '') . '>'. Raptor::get_string('int') .'</option><option value="float" ' . ($param['type'] == 'float' ? 'selected' : '') . '>'. Raptor::get_string('float') .'</option><option ' . ($param['type'] == 'str' ? 'selected' : '') . ' value="str">'. Raptor::get_string('string') .'</option><option ' . ($param['type'] == 'id' ? 'selected' : '') . ' value="id">Character ID</option><option ' . ($param['type'] == 'script' ? 'selected' : '') . ' value="script" >'. Raptor::get_string('script') .'</option></select></div>
		<div class="form-group" style="display: ' . ($param['type'] == 'script' ? 'block' : 'none') . ';" id="script_text"><label>Формула</label><input name="script" value="' . $param['script'] . '" class="form-control"><p class="help-block">PHP-code. char() - '. Raptor::get_string('char') .'. '. Raptor::get_string('example') .': <b>return char()->name;</b></p></div>
		<div class="form-group"><label>'. Raptor::get_string('default') .'</label><input name="def" value="' . $param['def'] . '" class="form-control"><p class="help-block"></p></div>
		<button type="submit" class="btn btn-default">'. Raptor::get_string('save') .'</button>
		</form>';
} 
else 
{
    echo base64_decode('PGgyPiYjMTA1NTsmIzEwNzI7JiMxMDg4OyYjMTA3MjsmIzEwODQ7JiMxMDc3OyYjMTA5MDsmIzEw
		ODg7JiMxMDk5OyAmIzEwODc7JiMxMDc3OyYjMTA4ODsmIzEwODk7JiMxMDg2OyYjMTA4NTsmIzEw
		NzI7JiMxMDc4OyYjMTA3Mjs8L2gyPg0KPGg1PiYjMTA1NjsmIzEwNzI7JiMxMDc5OyYjMTA4Mzsm
		IzEwODA7JiMxMDk1OyYjMTA4NTsmIzEwOTk7JiMxMDc3OyAmIzEwOTM7JiMxMDcyOyYjMTA4ODsm
		IzEwNzI7JiMxMDgyOyYjMTA5MDsmIzEwNzc7JiMxMDg4OyYjMTA4MDsmIzEwODk7JiMxMDkwOyYj
		MTA4MDsmIzEwODI7JiMxMDgwOyAmIzEwODc7JiMxMDc3OyYjMTA4ODsmIzEwODk7JiMxMDg2OyYj
		MTA4NTsmIzEwNzI7JiMxMDc4OyYjMTA3NzsmIzEwODE7PC9hPjwvaDU+DQo8YnI+DQo8Zm9ybSBt
		ZXRob2Q9J1BPU1QnPg0KPHA+PGlucHV0IHR5cGU9J3RleHQnIG5hbWU9J25hbWUnIHZhbHVlPSdw
		Xyc+PC9wPg0KPHA+PGJ1dHRvbiBuYW1lPSJuZXciIHR5cGU9InN1Ym1pdCIgdmFsdWU9IjEiIGNs
		YXNzPSJidG4gYnRuLXhzIGJ0bi1kZWZhdWx0Ij4mIzEwNTc7JiMxMDg2OyYjMTA3OTsmIzEwNzY7
		JiMxMDcyOyYjMTA5MDsmIzExMDA7ICYjMTA4NzsmIzEwNzI7JiMxMDg4OyYjMTA3MjsmIzEwODQ7
		JiMxMDc3OyYjMTA5MDsmIzEwODg7PC9idXR0b24+PC9wPg0KPC9mb3JtPg0KPGhyPg==');
    echo base64_decode('PGRpdiBjbGFzcz0idGFibGUtcmVzcG9uc2l2ZSI+DQo8dGFibGUgY2xhc3M9InRhYmxlIHRhYmxl
		LWJvcmRlcmVkIHRhYmxlLWhvdmVyIHRhYmxlLXN0cmlwZWQiPg0KPHRoZWFkPg0KPHRyPg0KICAg
		IDx0ZD4mIzEwNTM7JiMxMDcyOyYjMTA3OTsmIzEwNzQ7JiMxMDcyOyYjMTA4NTsmIzEwODA7JiMx
		MDc3OzwvdGQ+DQoJPHRkPiYjMTA1MDsmIzEwODY7JiMxMDc2OzwvdGQ+DQogICAgPHRkPjwvdGQ+
		DQo8L3RyPg0KPC90aGVhZD4NCjx0Ym9keT4=');
    foreach (Raptor::ModConfig('params') as $key => $value) 
	{
        if (!strstr($key, "p_")) 
		{
            continue;
        }
        echo "<tr><td> <b><font size=3>" . $value['name'] . "</font></b> </td> <td> <b><font size=3>" . $key . "</font></b> </td> <td> <a href='?edit=" . $key . "'>". Raptor::get_string('edit') ."</a> </td></tr>";
    }
    echo base64_decode('PC90Ym9keT4NCjwvdGFibGU+DQo8L2Rpdj4=');
}
?>