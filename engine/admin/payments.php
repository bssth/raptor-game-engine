<?php
	if (isset($_POST['mod'])) 
	{
		Raptor::SetModConfig("_payments", $_POST);
		echo "<div class='alert alert-success'>". Raptor::get_string('admin_saved') .". <a href=?>". Raptor::get_string('refresh') ."</a></div>";
	}
	
	$psconfig = Raptor::ModConfig('_payments');
?>

<script>
    function generateNewID() 
	{
        $.get('/api?a=uniqid', 
			function (data) 
			{
				document.getElementById('id').value = data;
			}
		);
    }
</script>


<form action="" method="POST">
	<div class="form-group"><label><?=Raptor::get_string('module')?></label>
		<select name="pay_mod" class="form-control">'; 
		<?php
			foreach($GLOBALS['modules'] as $mod) 
			{
				echo '<option '. ($psconfig['pay_mod']==$mod?'selected':'') .' value="'. $mod .'">'. $mod .'</option>';
			}
		?>
		</select>
	</div>
    <div class="form-group">
        <label>Secret Key</label>
        <input class="form-control" name="secret_key" value="<?= $psconfig['secret_key'] ?>">
    </div>
	<div class="form-group">
        <label>Driver</label>
        <input class="form-control" name="pay_driver" value="<?= $psconfig['pay_driver'] ?>">
    </div>
	<div class="form-group">
        <label>Class</label>
        <input class="form-control" name="pay_class" value="<?= $psconfig['pay_class'] ?>">
    </div>
	<div class="form-group">
        <label>DB Collection</label>
        <input class="form-control" name="pay_class" value="<?= $psconfig['pay_class'] ?>">
    </div>
	<div class="form-group">
        <label>Public Key</label>
        <input class="form-control" name="secret_key" value="<?= $psconfig['secret_key'] ?>">
    </div>
	<div class="form-group">
        <label>Secret Key</label>
        <input class="form-control" name="secret_key" value="<?= $psconfig['secret_key'] ?>">
    </div>
    <button name="mod" value="_payments" type="submit" class="btn btn-default">Сохранить</button>
</form>