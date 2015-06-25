<?php
if (isset($_POST['mod'])) {
    Database::Edit("config", array("mod" => "_payments"), $_POST);
    echo "<div class='alert alert-success'>Настройки сохранены. <a href=?>Обновить страницу</a></div>";
}
	$psconfig = Database::GetOne("config", array("mod" => "_payments"));
?>

<script>
    function generateNewID() {
        $.get('/api?a=uniqid', function (data) {
            document.getElementById('id').value = data;
        });
    }
</script>

<h4>Обратите внимание! В большинстве случаев модуль каждой платёжной системы имеет свои дополнительные настройки</h4>

<form action="" method="POST">
	<div class="form-group"><label>Модуль платёжной системы</label>
		<select name="pay_mod" class="form-control">'; 
		<?php
			foreach($GLOBALS['modules'] as $mod) {
				echo '<option '. ($psconfig['pay_mod']==$mod?'selected':'') .' value="'. $mod .'">'. $mod .'</option>';
			}
		?>
		</select>
	</div>
    <div class="form-group">
        <label>Секретный ключ (выдаётся платёжной системой)</label>
        <input class="form-control" name="secret_key" value="<?= $psconfig['secret_key'] ?>">
    </div>
	<div class="form-group">
        <label>Драйвер оплаты (обычно поле заполняется после установки модуля)</label>
        <input class="form-control" name="pay_driver" value="<?= $psconfig['pay_driver'] ?>">
    </div>
	<div class="form-group">
        <label>Класс платёжной системы (обычно поле заполняется после установки модуля)</label>
        <input class="form-control" name="pay_class" value="<?= $psconfig['pay_class'] ?>">
    </div>
	<div class="form-group">
        <label>Коллекция в БД с платежами (обычно payments)</label>
        <input class="form-control" name="pay_class" value="<?= $psconfig['pay_class'] ?>">
    </div>
	<div class="form-group">
        <label>Публичный ключ (выдаётся платёжной системой)</label>
        <input class="form-control" name="secret_key" value="<?= $psconfig['secret_key'] ?>">
    </div>
	<div class="form-group">
        <label>Секретный ключ (выдаётся платёжной системой)</label>
        <input class="form-control" name="secret_key" value="<?= $psconfig['secret_key'] ?>">
    </div>
    <button name="mod" value="_payments" type="submit" class="btn btn-default">Сохранить</button>
</form>