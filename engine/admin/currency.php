<?php
if (isset($_POST['new'])) {
    Database::Edit("config", array("mod" => "currency"), array("mod" => "currency", $_POST['name'] => array()));
    echo '<div class="alert alert-success">Валюта <b>' . $_POST['name'] . '</b> успешно создана</div>';
}
if (isset($_GET['edit'])) {
    if (isset($_POST['name'])) {
        Database::Edit("config", array("mod" => "currency"), array($_GET['edit'] => $_POST));
        echo '<div class="alert alert-success">Валюта <b>' . $_GET['edit'] . '</b> успешно отредактирована</div>';
    }
    $param = Database::GetOne("config", array("mod" => "currency"))[$_GET['edit']];
    echo '<form action="" method="POST">
		<div class="form-group"><label for="disabledSelect">Код валюты</label><input class="form-control" id="disabledInput" placeholder="' . $_GET['edit'] . '" disabled="" type="text"></div>
		<div class="form-group"><label>Название валюты</label><input name="name" value="' . $param['name'] . '" class="form-control"><p class="help-block">Название, отображаемое игрокам</p></div>
		<div class="form-group"><label>Название валюты на английском</label><input name="name_en" value="' . $param['name_en'] . '" class="form-control"></div>
		<div class="form-group"><label>Значок валюты (прямая ссылка; загрузка файла ниже)</label><input id="c_img" name="img" value="' . $param['img'] . '" class="form-control"></div>
		<div class="form-group"></div>
		<button type="submit" class="btn btn-default">Сохранить</button>
		</form>';
} else {
    echo '<h2>Валюты</h2>
		<h5>Различные игровые валюты</h5>
		<br>
		<form method="POST">
		<p><input name="name" value="money_" type="text"></p>
		<p><button name="new" type="submit" value="1" class="btn btn-xs btn-default">Создать валюту</button></p>
		</form>
		<hr>
		<div class="table-responsive">
		<hr><table class="table table-bordered table-hover table-striped">
		<thead>
		<tr>
			<td>Название</td>
			<td>Код</td>
			<td></td>
		</tr>
		</thead>
		<tbody>';
    foreach (Database::GetOne("config", array("mod" => "currency")) as $key => $value) {
        if (!strstr($key, "money_")) {
            continue;
        }
        echo "<tr><td> <b><font size=3>" . $value['name'] . "</font></b> </td> <td> <b><font size=3>" . $key . "</font></b> </td> <td> <a href='?edit=" . $key . "'>Редактировать</a> </td></tr>";
    }
    echo '</tbody>
		</table>
		</div>';
}
?>