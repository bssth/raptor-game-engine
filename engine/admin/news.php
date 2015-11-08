<h2>Новости</h2>
<br>
<form method="POST"><p><button name="new" type="submit" value="1" class="btn btn-xs btn-default"><?=Raptor::get_string('create')?></button></p></form>
<hr>

<?php
if (isset($_POST['title'])) 
{
	$_POST['_id'] = toId($_GET['edit']);
	News::edit($_GET['edit'], $_POST);
    echo '<div class="alert alert-success">'. Raptor::get_string('admin_saved') .'</div>';
}
if(isset($_POST['new'])) 
{
	$id = new MongoId();
	News::create($id);
	die("<script>location.href = '/admin/news?edit=". $id ."';</script>");
}
if (isset($_GET['edit'])) 
{
    $array = News::get($_GET['edit']);
    echo "<form action='' method='POST'>
		<input class='form-control' name='title' value='" . $array['title'] . "' placeholder='Заголовок'>
		<textarea rows=15 cols=105 placeholder='". Raptor::get_string('announce') ."' name='short'>" . $array['short'] . "</textarea> <br>
        <textarea rows=15 cols=105 placeholder='". Raptor::get_string('full_text') ."' name='full'>" . $array['full'] . "</textarea> <br>
        <button type='submit' class='btn btn-default'>". Raptor::get_string('save') ."</button>
        </form>
        <hr>";
}
?>

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <td><?=Raptor::get_string('title')?></td>
                <td></td>
            </tr>
        </thead>
        <tbody>

            <?php
            $arrays = News::get();
            foreach ($arrays as $array) 
			{
                echo "<tr><td> <b><font size=3>" . $array['title'] . "</font></b> </td><td> <a href='?edit=" . $array['_id'] . "'>". Raptor::get_string('edit') ."</a> </td></tr>";
            }
            ?>

        </tbody>
    </table>
</div>