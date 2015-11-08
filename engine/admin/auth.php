<?php
if (isset($_POST['mod'])) 
{
	Raptor::SetModConfig('auth', $_POST);
    echo "<div class='alert alert-success'>". Raptor::get_string('admin_saved') ."</div>";
}

$data = Raptor::ModConfig('auth');
?>

<form action="" method="POST">
    <div class="form-group">
        <label><?=Raptor::get_string('max_chars_per_player')?></label>
        <input class="form-control" name="maxchars" value="<?= $data['maxchars']; ?>">
    </div>

    <div class="form-group">
        <label><?=Raptor::get_string('enable_register')?></label>
        <div class="radio">
            <label>
                <input name="allowRegister" id="optionsRadios1" value="1" <?= ($data['allowRegister'] == 1) ? 'checked=""' : '' ?> type="radio">Да
            </label>
            | 
            <label>
                <input name="allowRegister" id="optionsRadios1" value="0" <?= ($data['allowRegister'] == 0) ? 'checked=""' : '' ?> type="radio">Нет
            </label>
        </div>
    </div>
	<div class="form-group"><label><?=Raptor::get_string('start_loc')?></label>
		<select name="start" class="form-control">'; 
		<?php
			foreach(Raptor::ModConfig('locations') as $key => $value) 
			{
				if(!is_array($value)) 
				{ 
					continue; 
				}
				echo '<option '. ($data['start']==$key?'selected':'') .' value="'. $key .'">'. $value['name'] .'</option>';
			}
		?>
		</select>
	</div>
	<div class="form-group">
        <label><?=Raptor::get_string('start_pos')?></label>
        <input class="form-control" name="start_x" placeholder="Ось Х" value="<?= $data['start_x']; ?>">
		<input class="form-control" name="start_y" placeholder="Ось Y" value="<?= $data['start_y']; ?>">
		<select name="start_dir" class="form-control">
			<option value="bottom">-- <?=Raptor::get_string('angle')?> --</option>
			<option <?=($data['start_dir']=='bottom'?'selected':'')?> value="bottom"><?=Raptor::get_string('bottom')?></option>
			<option <?=($data['start_dir']=='top'?'selected':'')?> value="top"><?=Raptor::get_string('up')?></option>
			<option <?=($data['start_dir']=='right'?'selected':'')?> value="right"><?=Raptor::get_string('right')?></option>
			<option <?=($data['start_dir']=='left'?'selected':'')?> value="left"><?=Raptor::get_string('left')?></option>
		</select>
    </div>
    <div class="form-group">
        <label><?=Raptor::get_string('login_using')?>...</label>
        <select name="authType" class="form-control">
            <option value="login" <?= ($data['authType'] == 'login') ? 'selected=""' : '' ?>>Login</option>
            <option value="email" <?= ($data['authType'] == 'email') ? 'selected=""' : '' ?>>E-MAIL</option>
        </select>
    </div>

    <button type="submit" name="mod" value="auth" class="btn btn-default"><?=Raptor::get_string('save')?></button>
</form>
