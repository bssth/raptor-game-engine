<?php
if (isset($_POST['name'])) {
    Database::Save("config", array("active" => 1), $_POST);
    echo "<div class='alert alert-success'>Настройки сохранены. <a href=?>Обновить страницу</a></div>";
}
?>
<script>
    function generateNewID() {
        $.get('/api?a=uniqid', function (data) {
            document.getElementById('id').value = data;
        });
    }
</script>

<form action="" method="POST">
    <div class="form-group">
        <label>Название игры</label>
        <input class="form-control" name="name" value="<?= $GLOBALS['name'] ?>">
    </div>
    <div class="form-group">
        <label>ID игры</label>
        <input class="form-control" id="id" name="id" value="<?= $GLOBALS['id'] ?>">
        <p class="help-block">Уникальный идентификатор игры для каталога RAPTOR. Генерируется при установке</p>
        <p class="help-block"><a href='#' onclick="generateNewID()">Сгенерировать новый</a></p>
    </div>
    <div class="form-group">
        <label>Версия игры</label>
        <input class="form-control" name="version" value="<?= $GLOBALS['version'] ?>">
    </div>
    <div class="form-group">
        <label>Public Key (публичный ключ для API)</label>
        <input class="form-control" name="public_key" value="<?= $GLOBALS['public_key'] ?>">
    </div>
    <div class="form-group">
        <label>Private Key (приватный ключ для API; не сообщайте его сторонним лицам)</label>
        <input class="form-control" name="private_key" value="<?= $GLOBALS['private_key'] ?>">
    </div>
    <div class="form-group">
        <label for="disabledSelect">База данных MongoDB</label>
        <input class="form-control" id="disabledInput" value="<?= $GLOBALS['database']; ?>" disabled="" type="text">
        <p class="help-block">Редактируйте вручную в config.php</p>
    </div>
    <button type="submit" class="btn btn-default">Сохранить</button>
</form>

