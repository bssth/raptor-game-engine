<link href="/storage/admin/scredit.css" rel="stylesheet">
<script src="/storage/admin/scredit.js"></script>

<h2><?=Raptor::get_string('scripts')?></h2>
<h5><?=Raptor::get_string('acp_scripts')?></h5>
<br>
<hr>

<?php
    if(isset($_POST['file'])) 
	{
		Database::Edit('scripts', array('name' => $_POST['file']), array('code' => base64_encode($_POST['edit'])));
		Cache::set("script_". $_POST['file'], base64_encode($_POST['edit']), 86400);
		echo '<div class="alert alert-success">'. Raptor::get_string('script') . ' ' . Raptor::get_string('edited') . '</div>';
    }
    if(isset($_GET['edit'])) 
	{
        $content = isset($_POST['edit']) ? $_POST['edit'] : base64_decode(Database::GetOne('scripts', array('name' => $_GET['edit']))['code']);
        echo "<form action='' method='POST'>
        <input type='hidden' name='file' value='". $_GET['edit'] ."'>
        <textarea rows=15 cols=105 name='edit'>". $content ."</textarea> <br>
        <button type='submit' class='btn btn-default'>". Raptor::get_string('save') ."</button>
        </form>
        <hr>";
    }
?>
<div class="table-responsive">
<table class="table table-bordered table-hover table-striped">
<thead>
<tr>
    <td><?=Raptor::get_string('script')?></td>
    <td></td>
</tr>
</thead>
<tbody>

<?php
	$files = Database::GetAll('scripts');
	foreach($files as $file) 
	{
		echo "<tr><td> <b><font size=3>". $file['name'] ."</font></b> </td><td> <a href='?edit=". $file['name'] ."'>". Raptor::get_string('edit') ."</a> </td></tr>";
	}

?>

</tbody>
</table>
</div>