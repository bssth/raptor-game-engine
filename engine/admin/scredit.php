<?php
if (isset($_POST['mod'])) 
{
    Raptor::SetModConfig($_POST);
	foreach($_POST as $key => $value) 
	{
		Cache::set("rpgjs_cmd" . $key, $value, 3600);
	}
    echo "<div class='alert alert-success'>". Raptor::get_string('admin_saved') .". ". Raptor::get_string('cache_flush') .".</div>";
}

$data = Raptor::ModConfig('locations');
?>

<div class="well"><?=Raptor::get_string('rpgjs_cmd')?></div>

<form action="" method="POST">
    <div class="form-group">
        <label><?=Raptor::get_string('onrun_cmd')?></label>
        <textarea class="form-control" name="_onrun" rows="3"><?= $data['_onrun']; ?></textarea>
    </div>
    <div class="form-group">
        <label><?=Raptor::get_string('onloop_cmd')?></label>
        <textarea class="form-control" name="_onsync" rows="3"><?= $data['_onsync']; ?></textarea>
    </div>
    <button type="submit" name="mod" value="locations" class="btn btn-default"><?=Raptor::get_string('save')?></button>
</form>
