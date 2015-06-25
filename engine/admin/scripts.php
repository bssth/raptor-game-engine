<link href="/storage/admin/scredit.css" rel="stylesheet">
<script src="/storage/admin/scredit.js"></script>

<h2>Скрипты</h2>
<h5>Здесь вы можете редактировать PHP скрипты. Для изменения Javascript используйте <a href='/admin/scredit'>JS SCREdit</a></h5>
<br>
<hr>

<?php
    if(isset($_POST['file'])) {
		Database::Edit('scripts', array('name' => $_POST['file']), array('code' => base64_encode($_POST['edit'])));
		echo '<div class="alert alert-success">Скрипт успешно отредактирован</div>';
    }
    if(isset($_GET['edit'])) {
        $content = isset($_POST['edit']) ? $_POST['edit'] : base64_decode(Database::GetOne('scripts', array('name' => $_GET['edit']))['code']);
        echo "<form action='' method='POST'>
        <input type='hidden' name='file' value='". $_GET['edit'] ."'>
        <textarea rows=15 cols=105 name='edit'>". $content ."</textarea> <br>
        <button type='submit' class='btn btn-default'>Сохранить</button>
        </form>
        <hr>";
    }
?>
<div class="table-responsive">
<table class="table table-bordered table-hover table-striped">
<thead>
<tr>
    <td>Скрипт</td>
    <td></td>
</tr>
</thead>
<tbody>

<?php
	$files = Database::GetAll('scripts');
	foreach($files as $file) {
		echo "<tr><td> <b><font size=3>". $file['name'] ."</font></b> </td><td> <a href='?edit=". $file['name'] ."'>Редактировать</a> </td></tr>";
	}

?>

</tbody>
</table>
</div>