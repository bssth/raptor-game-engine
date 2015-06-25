<?php
if (isset($_POST['mod'])) { 
    Database::Edit("config", array("mod" => "locations"), $_POST);
    echo "<div class='alert alert-success'>Настройки применены</div>";
}

$data = Database::GetOne("config", array("mod" => "locations"));
?>

<div class="well">Здесь можно управлять графикой, которая доступна в игре. Каждая настройка - массив в формате JSON. Ключи массива - целые числа, идентификаторы элемента, а значения - имена файлов. Каждый массив работает с отдельной папкой графики, которая указана в подсказке (по отношению директории <b>/storage/static/Graphics</b>)</div>

<form action="" method="POST">
    <div class="form-group">
        <label>Персонажи (скины персонажей, папка Characters)</label>
        <textarea class="form-control" name="_graphic_characters" rows="3"><?= $data['_graphic_characters']; ?></textarea>
    </div>
	<div class="form-group">
        <label>Тайлы (наборы фонов - трава, камень, пол, папка Tilesets)</label>
        <textarea class="form-control" name="_tilesets" rows="3"><?= $data['_tilesets']; ?></textarea>
    </div>
	<div class="form-group">
        <label>Автотайлы (наборы тайлов)</label>
        <textarea class="form-control" name="_autotiles" rows="3"><?= $data['_autotiles']; ?></textarea>
    </div>
	<div class="form-group">
        <label>Музыкальное сопровождение (музыка, которая звучит в игре, папка /storage/static/Audio/BGM)</label>
        <textarea class="form-control" name="_music" rows="3"><?= $data['_music']; ?></textarea>
    </div>
	<div class="form-group">
        <label>База данных RPG.JS (изменяйте настройку только если понимаете её смысл)</label>
        <textarea class="form-control" name="_rpgjs_database" rows="3"><?= $data['_rpgjs_database']; ?></textarea>
    </div>
	<div class="form-group">
        <label>Дефайны RPG.JS (изменяйте настройку только если понимаете её смысл)</label>
        <textarea class="form-control" name="_defines" rows="3"><?= $data['_defines']; ?></textarea>
    </div>
    <button type="submit" name="mod" value="locations" class="btn btn-default">Сохранить</button>
</form>