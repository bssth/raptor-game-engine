<?php
if (isset($_POST['name'])) 
{
    Raptor::SetConfig($_POST);
    echo "<div class='alert alert-success'>". Raptor::get_string('admin_saved') .". ". Raptor::get_string('cache_flush') .". <a href=?>". Raptor::get_string('cache_flush') ."</a></div>";
}
?>
<script>
    function generateNewID() 
	{
        $.get('/api?a=uniqid', 
			function (data) {
				document.getElementById('id').value = data;
			}, 'text'
		);
    }
</script>

<form action="" method="POST">
    <div class="form-group">
        <label><?=Raptor::get_string('title')?></label>
        <input class="form-control" name="name" value="<?= $GLOBALS['name'] ?>">
    </div>
    <div class="form-group">
        <label>ID</label>
        <input class="form-control" id="id" name="id" value="<?= $GLOBALS['id'] ?>">
        <p class="help-block"></p>
        <p class="help-block"><a href='#' onclick="generateNewID()">Generate</a></p>
    </div>
    <div class="form-group">
        <label>Version</label>
        <input class="form-control" name="version" value="<?= $GLOBALS['version'] ?>">
    </div>
    <div class="form-group">
        <label>Public Key</label>
        <input class="form-control" name="public_key" value="<?= $GLOBALS['public_key'] ?>">
    </div>
    <div class="form-group">
        <label>Private Key</label>
        <input class="form-control" name="private_key" value="<?= $GLOBALS['private_key'] ?>">
    </div>
	<div class="form-group">
        <label>RCON</label>
        <input class="form-control" name="rcon" value="<?= $GLOBALS['rcon'] ?>">
    </div>
	<div class="form-group">
        <label>Language</label>
        <input class="form-control" name="language" value="<?= $GLOBALS['language'] ?>">
		<p class="help-block">en - English, ru - Русский</p>
    </div>
    <div class="form-group">
        <label for="disabledSelect">MongoDB</label>
        <input class="form-control" id="disabledInput" value="<?= $GLOBALS['database']; ?>" disabled="" type="text">
        <p class="help-block">Edit in config.php</p>
    </div>
    <button type="submit" name="active" value="1" class="btn btn-default"><?=Raptor::get_string('save')?></button>
</form>