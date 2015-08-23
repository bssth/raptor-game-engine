<?php
if (isset($_POST['new'])) 
{
    Database::Edit("config", array("mod" => "char_actions"), array("mod" => "char_actions", $_POST['name'] => array()));
    echo '<div class="alert alert-success">Действие <b>' . $_POST['name'] . '</b> успешно создано</div>';
}
if (isset($_GET['edit'])) 
{
    if (isset($_POST['name'])) 
	{
        Database::Edit("config", array("mod" => "char_actions"), array($_GET['edit'] => $_POST));
        echo '<div class="alert alert-success">Действие <b>' . $_GET['edit'] . '</b> успешно отредактировано</div>';
    }
    $param = Database::GetOne("config", array("mod" => "char_actions"))[$_GET['edit']];
    echo '<form action="" method="POST">
		<div class="form-group"><label for="disabledSelect">Код действия</label><input class="form-control" id="disabledInput" placeholder="' . $_GET['edit'] . '" disabled="" type="text"></div>
		<div class="form-group"><label>Название действия</label><input name="name" value="' . $param['name'] . '" class="form-control"><p class="help-block">Название, отображаемое игрокам</p></div>
		<button type="submit" class="btn btn-default">Сохранить</button>
		</form>';
} 
else
{
    raptor_print('PGg1PtCg0LDQt9C70LjRh9C90YvQtSDQtNC10LnRgdGC0LLQuNGPINC90LDQtCDQv9C10YDRgdC+0L3QsNC20LDQvNC4PC9oNT4NCjxicj4NCtCX0LTQtdGB0Ywg0LLRiyDQvNC+0LbQtdGC0LUg0L7RgtGA0LXQtNCw0LrRgtC40YDQvtCy0LDRgtGMINC00LXQudGB0YLQstC40Y8sINC60L7RgtC+0YDRi9C1INC80L7QttC10YIg0YHQvtCy0LXRgNGI0LDRgtGMINC+0LTQuNC9INC/0LXRgNGB0L7QvdCw0LYg0L3QsNC0INC00YDRg9Cz0LjQvCAo0L3QsNC/0YDQuNC80LXRgDog0L3QsNC/0LDRgdGC0YwsINC+0YLQv9GA0LDQstC40YLRjCDRgdC+0L7QsdGJ0LXQvdC40LUpDQo8Zm9ybSBtZXRob2Q9IlBPU1QiPg0KPHA+PGlucHV0IG5hbWU9Im5hbWUiIHZhbHVlPSJhY3RfIiB0eXBlPSJ0ZXh0Ij48L3A+DQo8cD48YnV0dG9uIG5hbWU9Im5ldyIgdHlwZT0ic3VibWl0IiB2YWx1ZT0iMSIgY2xhc3M9ImJ0biBidG4teHMgYnRuLWRlZmF1bHQiPtCh0L7Qt9C00LDRgtGMINC00LXQudGB0YLQstC40LU8L2J1dHRvbj48L3A+DQo8L2Zvcm0+DQo8aHI+DQo8ZGl2IGNsYXNzPSJ0YWJsZS1yZXNwb25zaXZlIj48dGFibGUgY2xhc3M9InRhYmxlIHRhYmxlLWJvcmRlcmVkIHRhYmxlLWhvdmVyIHRhYmxlLXN0cmlwZWQiPg0KPHRoZWFkPg0KPHRyPg0KICAgIDx0ZD7QndCw0LfQstCw0L3QuNC1PC90ZD4NCgk8dGQ+0JrQvtC0PC90ZD4NCiAgICA8dGQ+PC90ZD4NCjwvdHI+DQo8L3RoZWFkPg==');
    foreach (Database::GetOne("config", array("mod" => "char_actions")) as $key => $value) 
	{
        if (!strstr($key, "act_")) 
		{
            continue;
        }
        echo "<tr><td> <b><font size=3>" . $value['name'] . "</font></b> </td> <td> <b><font size=3>" . $key . "</font></b> </td> <td> <a href='?edit=" . $key . "'>Редактировать</a> </td></tr>";
    }
    raptor_print('PC90Ym9keT4NCjwvdGFibGU+DQo8L2Rpdj4=');
}
?>