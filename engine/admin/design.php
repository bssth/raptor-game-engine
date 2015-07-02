<h2>Дизайн</h2>
<h5>Шаблоны; также их можно найти в папке <b>/engine/template</b></h5>
<br>
<hr>

<?php
if (isset($_POST['file'])) {
    $file = TEMPLATE_ROOT . SEPARATOR . $_POST['file'];
    if (is_writable($file) or ! file_exists($file)) {
        $fp = fopen($file, 'w');
        fwrite($fp, $_POST['edit']);
        fclose($fp);
        echo '<div class="alert alert-success">Шаблон успешно отредактирован. Кэш шаблона очищен.</div>';
		Cache::set(sha1($file), $_POST['edit'], 3600);
    } else {
        echo '<div class="alert alert-danger">Данный файл недоступен для записи. Измените права доступа</div>';
    }
}
if (isset($_GET['edit'])) {
    echo "<form action='' method='POST'>
        <input type='hidden' name='file' value='" . $_GET['edit'] . "'>
        <textarea rows=15 cols=105 name='edit'>" . file_get_contents(TEMPLATE_ROOT . SEPARATOR . $_GET['edit']) . "</textarea> <br>
        <button type='submit' class='btn btn-default'>Сохранить</button>
        </form>
        <hr>";
}
?>
<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <td>Шаблон</td>
                <td></td>
            </tr>
        </thead>
        <tbody>

            <?php
            $skip = array('.', '..', '.htaccess', '.conf');
            $files = scandir(TEMPLATE_ROOT);
            foreach ($files as $file) {
                if (!in_array($file, $skip)) {
					if(is_dir(TEMPLATE_ROOT . SEPARATOR . $file)) { 
						echo "<tr><td> <b><font size=3>" . $file . ":</font></b> </td><td></td></tr>";
						$lst = scandir(TEMPLATE_ROOT . SEPARATOR . $file);
						foreach($lst as $f) {
							if(in_array($f, $skip)) { continue; }
							echo "<tr><td> <b><font size=3>". $file . SEPARATOR . $f . "</font></b> </td><td> <a href='?edit=". $file . SEPARATOR . $f . "'>Редактировать</a> </td></tr>";
						}
					}
					else {
						echo "<tr><td> <b><font size=3>" . $file . "</font></b> </td><td> <a href='?edit=" . $file . "'>Редактировать</a> </td></tr>";
					}
                }
            }
            ?>

        </tbody>
    </table>
</div>