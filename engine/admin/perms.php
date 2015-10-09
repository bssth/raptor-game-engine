<h2>Права доступа</h2>
<h4>Здесь можно настроить права доступа для любого игрока. Обратите вниманиe, что наименования прав доступа - это разделы админ-панели, к которым имеет доступ игрок</h4>

<?php
if (isset($_POST['submit'])) 
{
    unset($_POST['submit']);
    Char::find(array("name" => $_GET['name']))->perms = array_keys($_POST);
    echo '<div class="alert alert-success">Права доступа изменены успешно</div>';
}
if (isset($_GET['name'])) 
{
    $chara = Char::find(array("name" => $_GET['name']));
    if (!isset($chara['_id'])) 
	{
        echo '<div class="alert alert-danger">Персонаж не найден</div>';
    } 
	else 
	{
        $skip = array('.', '..', '.htaccess', '.conf', 'header.inc.php', 'footer.inc.php');
        $files = scandir(ADMIN_ROOT);
        $stack = $chara['perms'];
        echo '<div class="table-responsive">
                      <table class="table table-bordered table-hover table-striped">
                      <thead>
                      <tr>
                          <td>Наименование</td>
                          <td></td>
                      </tr>
                      </thead>
                      <tbody>';
        foreach ($files as $file) 
		{
            if (!in_array($file, $skip)) 
			{
                $file = str_replace(".php", "", $file);
                $value = (in_array($file, $stack)) ? 'checked' : '';
                echo "<tr><td><b><font size=3>" . $file . "</font></b></td><td><input form='perms' name='" . $file . "' " . $value . " value='1' type='checkbox'></td></tr>";
            }
        }
        echo "</tbody></table></div>";
        echo '<form method="POST" name="perms" id="perms"><button type="submit" name="submit" value="1" class="btn btn-default">Сохранить</button></form>';
    }
}
?>
<hr>
<form role="form" method="GET">
    <div class="form-group input-group">
        <input class="form-control" value="<?= $_GET['name']; ?>" name="name" type="text">
        <span class="input-group-btn"><button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button></span>
    </div>
    <button type="submit" class="btn btn-default">Поиск</button>
</form>