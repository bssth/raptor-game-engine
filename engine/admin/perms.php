<h2><?=Raptor::get_string('perms')?></h2>

<?php
if (isset($_POST['submit'])) 
{
    unset($_POST['submit']);
    Char::find(array("name" => $_GET['name']))->perms = array_keys($_POST);
    echo '<div class="alert alert-success">'. Raptor::get_string('admin_saved') .'</div>';
}
if (isset($_GET['name'])) 
{
    $chara = Char::find(array("name" => $_GET['name']));
    if (!isset($chara['_id'])) 
	{
        echo '<div class="alert alert-danger">'. Raptor::get_string('char_not_found') .'</div>';
    } 
	else 
	{
        $skip = array('.', '..', '.htaccess', '.conf', 'header.inc.php', 'footer.inc.php');
        $files = scandir(ADMIN_ROOT);
        $stack = is_array($chara['perms']) ? $chara['perms'] : array();
        echo '<div class="table-responsive">
                      <table class="table table-bordered table-hover table-striped">
                      <thead>
                      <tr>
                          <td>'. Raptor::get_string('filename') .'</td>
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
                echo "<tr><td><b><font size=3>" . (!empty(Raptor::get_string($file)) ? Raptor::get_string($file) : $file) . "</font></b></td><td><input form='perms' name='" . $file . "' " . $value . " value='1' type='checkbox'></td></tr>";
            }
        }
        echo "</tbody></table></div>";
        echo '<form method="POST" name="perms" id="perms"><button type="submit" name="submit" value="1" class="btn btn-default">'. Raptor::get_string('save') .'</button></form>';
    }
}
?>
<hr>
<form role="form" method="GET">
    <div class="form-group input-group">
        <input class="form-control" value="<?= $_GET['name']; ?>" name="name" type="text">
        <span class="input-group-btn"><button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button></span>
    </div>
    <button type="submit" class="btn btn-default"><?=Raptor::get_string('search')?></button>
</form>