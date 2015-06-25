<?php
if (isset($_POST['mod'])) {
    Database::Edit("config", array("mod" => "auth"), $_POST);
    echo "<div class='alert alert-success'>Настройки применены</div>";
}

$data = Database::GetOne("config", array("mod" => "auth"));
?>

<form action="" method="POST">
    <div class="form-group">
        <label>Максимум персонажей на игрока</label>
        <input class="form-control" name="maxchars" value="<?= $data['maxchars']; ?>">
    </div>

    <div class="form-group">
        <label>Включить регистрацию</label>
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
	<div class="form-group"><label>Стартовая локация</label>
		<select name="start" class="form-control">'; 
		<?php
			foreach(Database::GetOne("config", array("mod" => "locations")) as $key => $value) {
				if(!is_array($value)) { continue; }
				echo '<option '. ($data['start']==$key?'selected':'') .' value="'. $key .'">'. $value['name'] .'</option>';
			}
		?>
		</select>
	</div>
	<div class="form-group">
        <label>Стартовые координаты</label>
        <input class="form-control" name="start_x" placeholder="Ось Х" value="<?= $data['start_x']; ?>">
		<input class="form-control" name="start_y" placeholder="Ось Y" value="<?= $data['start_y']; ?>">
		<select name="start_dir" class="form-control">
			<option value="bottom">-- НАПРАВЛЕНИЕ --</option>
			<option <?=($data['start_dir']=='bottom'?'selected':'')?> value="bottom">Вниз</option>
			<option <?=($data['start_dir']=='top'?'selected':'')?> value="top">Вверх</option>
			<option <?=($data['start_dir']=='right'?'selected':'')?> value="right">Направо</option>
			<option <?=($data['start_dir']=='left'?'selected':'')?> value="left">Налево</option>
		</select>
    </div>
    <div class="form-group">
        <label>Входить в аккаунт используя...</label>
        <select name="authType" class="form-control">
            <option value="login" <?= ($data['authType'] == 'login') ? 'selected=""' : '' ?>>Логин</option>
            <option value="email" <?= ($data['authType'] == 'email') ? 'selected=""' : '' ?>>E-MAIL</option>
        </select>
    </div>

    <button type="submit" name="mod" value="auth" class="btn btn-default">Сохранить</button>
</form>
