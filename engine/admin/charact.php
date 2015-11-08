<?php
if (isset($_POST['new'])) 
{
    Raptor::SetModConfig('char_actions', array("mod" => "char_actions", $_POST['name'] => array()));
    echo '<div class="alert alert-success">'. Raptor::get_string('admin_saved') .'</div>';
}
if (isset($_GET['edit'])) 
{
    if (isset($_POST['name'])) 
	{
		Raptor::SetModConfig('char_actions', array("mod" => "char_actions", $_GET['edit'] => $_POST));
        echo '<div class="alert alert-success">'. Raptor::get_string('admin_saved') .'</div>';
    }
    $param = Raptor::ModConfig('char_actions')[$_GET['edit']];
    echo '<form action="" method="POST">
		<div class="form-group"><label for="disabledSelect">'. Raptor::get_string('code') .'</label><input class="form-control" id="disabledInput" placeholder="' . $_GET['edit'] . '" disabled="" type="text"></div>
		<div class="form-group"><label>'. Raptor::get_string('title') .'</label><input name="name" value="' . $param['name'] . '" class="form-control"><p class="help-block"></p></div>
		<button type="submit" class="btn btn-default">'. Raptor::get_string('save') .'</button>
		</form>';
} 
else
{
    raptor_print('PGg1PkNoYXJhY3RlciBhY3Rpb25zPC9oNT4NCjxicj4NCjxmb3JtIG1ldGhvZD0iUE9TVCI+DQo8cD48aW5wdXQgbmFtZT0ibmFtZSIgdmFsdWU9ImFjdF8iIHR5cGU9InRleHQiPjwvcD4NCjxwPjxidXR0b24gbmFtZT0ibmV3IiB0eXBlPSJzdWJtaXQiIHZhbHVlPSIxIiBjbGFzcz0iYnRuIGJ0bi14cyBidG4tZGVmYXVsdCI+TmV3PC9idXR0b24+PC9wPg0KPC9mb3JtPg0KPGhyPg0KPGRpdiBjbGFzcz0idGFibGUtcmVzcG9uc2l2ZSI+PHRhYmxlIGNsYXNzPSJ0YWJsZSB0YWJsZS1ib3JkZXJlZCB0YWJsZS1ob3ZlciB0YWJsZS1zdHJpcGVkIj4NCjx0aGVhZD4NCjx0cj4NCiAgICA8dGQ+VGl0bGU8L3RkPg0KCTx0ZD5Db2RlPC90ZD4NCiAgICA8dGQ+PC90ZD4NCjwvdHI+DQo8L3RoZWFkPg==');
    foreach (Raptor::ModConfig('char_actions') as $key => $value) 
	{
        if (!strstr($key, "act_")) 
		{
            continue;
        }
        echo "<tr><td> <b><font size=3>" . $value['name'] . "</font></b> </td> <td> <b><font size=3>" . $key . "</font></b> </td> <td> <a href='?edit=" . $key . "'>". Raptor::get_string('edit') ."</a> </td></tr>";
    }
    raptor_print('PC90Ym9keT4NCjwvdGFibGU+DQo8L2Rpdj4=');
}
?>