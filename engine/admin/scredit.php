<?php
if (isset($_POST['mod'])) 
{
    Database::Edit("config", array("mod" => "locations"), $_POST);
	foreach($_POST as $key => $value) 
	{
		Cache::set("rpgjs_cmd" . $key, $value, 3600);
	}
    echo "<div class='alert alert-success'>Настройки применены. Кэш обновлен.</div>";
}

$data = Database::GetOne("config", array("mod" => "locations"));
?>

<div class="well">Под <b>командами</b> здесь подразумеваются команды скриптового движка из RPG.JS в формате JSON<br> <a target="_blank" href='/ahelp/cmdlist'>Подробный список команд</a></div>

<form action="" method="POST">
    <div class="form-group">
        <label>Команды, вызываемые при запуске игры</label>
        <textarea class="form-control" name="_onrun" rows="3"><?= $data['_onrun']; ?></textarea>
    </div>
    <div class="form-group">
        <label>Команды, вызываемые каждые 1.5 секунд</label>
        <textarea class="form-control" name="_onsync" rows="3"><?= $data['_onsync']; ?></textarea>
    </div>
    <button type="submit" name="mod" value="locations" class="btn btn-default">Сохранить</button>
</form>
