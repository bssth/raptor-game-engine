<?php
if (isset($_POST['mod'])) 
{ 
	Raptor::SetModConfig('locations', $_POST);
    echo "<div class='alert alert-success'>". Raptor::get_string('admin_saved') ."</div>";
}

$data = Raptor::ModConfig('locations');
?>

<div class="well"><?=Raptor::get_string('gr_tip')?> <b>/storage/static/Graphics</b>)</div>

<form action="" method="POST">
    <div class="form-group">
        <label>Skins</label>
        <textarea class="form-control" name="_graphic_characters" rows="3"><?= $data['_graphic_characters']; ?></textarea>
    </div>
	<div class="form-group">
        <label>Tiles</label>
        <textarea class="form-control" name="_tilesets" rows="3"><?= $data['_tilesets']; ?></textarea>
    </div>
	<div class="form-group">
        <label>Autotiles</label>
        <textarea class="form-control" name="_autotiles" rows="3"><?= $data['_autotiles']; ?></textarea>
    </div>
	<div class="form-group">
        <label>Music</label>
        <textarea class="form-control" name="_music" rows="3"><?= $data['_music']; ?></textarea>
    </div>
	<div class="form-group">
        <label>RPG.JS Database</label>
        <textarea class="form-control" name="_rpgjs_database" rows="3"><?= $data['_rpgjs_database']; ?></textarea>
    </div>
	<div class="form-group">
        <label>RPG.JS Defines</label>
        <textarea class="form-control" name="_defines" rows="3"><?= $data['_defines']; ?></textarea>
    </div>
    <button type="submit" name="mod" value="locations" class="btn btn-default"><?=Raptor::get_string('save')?></button>
</form>