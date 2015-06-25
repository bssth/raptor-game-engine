

<?php
if (isset($_POST['new'])) {
    Database::Edit("config", array("mod" => "inv_params"), array("mod" => "inv_params", $_POST['name'] => array()));
    echo '<div class="alert alert-success">Параметр <b>' . $_POST['name'] . '</b> успешно создан</div>';
}
if (isset($_GET['edit'])) {
    if (isset($_POST['name'])) {
        Database::Edit("config", array("mod" => "inv_params"), array($_GET['edit'] => $_POST));
        echo '<div class="alert alert-success">Параметр <b>' . $_GET['edit'] . '</b> успешно отредактирован</div>';
    }
    $param = Database::GetOne("config", array("mod" => "inv_params"))[$_GET['edit']];
    echo '<form action="" method="POST">
		<div class="form-group"><label for="disabledSelect">Код параметра</label><input class="form-control" id="disabledInput" placeholder="' . $_GET['edit'] . '" disabled="" type="text"></div>
		<div class="form-group"><label>Название параметра</label><input name="name" value="' . $param['name'] . '" class="form-control"><p class="help-block">Название, отображаемое игрокам</p></div>
		<div class="form-group"><label>Название параметра на английском</label><input name="name_en" value="' . $param['name_en'] . '" class="form-control"></div>
		<div class="form-group"><label>Тип параметра</label><select name="type" onchange="if (this.selectedIndex == 3) document.getElementById(\'script_text\').style.display = \'block\'" class="form-control"><option value="int" ' . ($param['type'] == 'int' ? 'selected' : '') . '>Целое число</option><option value="float" ' . ($param['type'] == 'float' ? 'selected' : '') . '>Дробное число</option><option ' . ($param['type'] == 'str' ? 'selected' : '') . ' value="str">Строка</option><option ' . ($param['type'] == 'id' ? 'selected' : '') . ' value="id">ID персонажа</option><option ' . ($param['type'] == 'script' ? 'selected' : '') . ' value="script" >Формула (скриптовое выражение)</option></select></div>
		<div class="form-group" style="display: ' . ($param['type'] == 'script' ? 'block' : 'none') . ';" id="script_text"><label>Формула</label><input name="script" value="' . $param['script'] . '" class="form-control"><p class="help-block">PHP-код. Переменная $char - объект с персонажем, $inv - с инвентарём. Пример использования: <b>return $char->name;</b></p></div>
		<button type="submit" class="btn btn-default">Сохранить</button>
		</form>';
} else {
    echo '<div class="container-fluid">﻿<h2>Параметры предметов</h2>
<h5>Различные характеристики предметов</h5>
<br>
<form method="POST">
<p><input name="name" value="p_" type="text"></p>
<p><button name="new" type="submit" value="1" class="btn btn-xs btn-default">Создать параметр</button></p>
</form>
<hr><div class="table-responsive">
<table class="table table-hover table-striped"><tbody>';
    foreach (Database::GetOne("config", array("mod" => "inv_params")) as $key => $value) {
        if (!strstr($key, "p_")) {
            continue;
        }
        echo "<tr><td> <b><font size=3>" . $value['name'] . "</font></b> </td> <td> <b><font size=3>" . $key . "</font></b> </td> <td> <a href='?edit=" . $key . "'>Редактировать</a> </td></tr>";
    }
    echo '</tbody></table>';
    ;
}
?>