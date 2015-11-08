<?php
if (empty($_GET['id'])) 
{
    $_GET['id'] = $_SESSION['cid'];
}

if (isset($_POST['change'])) 
{
    unset($_POST['change']);
    Char::merge(array("_id" => toId($_GET['id'])), $_POST);
}
if (isset($_POST['make'])) 
{
	Char::merge(array("_id" => toId($_GET['id'])), array($_POST['name'] => '0'));
}
if (isset($_POST['notes'])) 
{
	Char::merge(array("_id" => toId($_GET['id'])), array("notes" => $_POST['notes']));
}

$obj = new Char($_GET['id']);
$schar = Char::find('_id', toId($_GET['id']));

if(isset($_GET['give'])) 
{
	$obj->inv->giveItem($_GET['give'], $_GET['cnt']);
}
if(isset($_GET['take'])) 
{
	$obj->inv->takeItem($_GET['take'], $_GET['cnt']);
}

if (empty($schar['_id'])) 
{
    echo '<div class="alert alert-danger">'. Raptor::get_string('char_not_found') .'</div>';
    die();
}
?>

<script>
	function giveItem(id, count) 
	{
		$.get("/admin/char?id=<?=$_GET['id'];?>&give=" + id + "&cnt=" + count, {}, function(data) { window.location = window.location; }, "text");
	}
	function takeItem(id, count) 
	{
		$.get("/admin/char?id=<?=$_GET['id'];?>&take=" + id + "&cnt=" + count, {}, function(data) { window.location = window.location; }, "text");
	}
</script>
<div class="row">
    <div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=Raptor::get_string('db_fields')?></h3>
            </div>
            <div class="panel-body">
				<?php
				foreach ($schar as $key => $value) 
				{
					if (is_array($key) or is_array($value)) 
					{
						continue;
					}
					echo "<p>" . $key . " = " . $value . "</p>";
				}
				?>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?=Raptor::get_string('change')?> <?=Raptor::get_string('db_fields')?></h3>
            </div>
            <div class="panel-body">
                <?php
                foreach ($schar as $key => $value) 
				{
                    if ($key == '_id' or is_object($value) or is_array($value)) 
					{
                        continue;
                    }
                    echo "<form method='POST'>"
                    . "<p>" . $key . " = <input type='text' value='" . $value . "' name='" . $key . "'>"
                    . '<button name="change" type="submit" value="1" class="btn btn-xs btn-default">'. Raptor::get_string('save') .'</button>'
                    . '</form></p>';
                }
                ?>
            </div>
        </div>
    </div>
    <!-- /.col-sm-4 -->
    <div class="col-sm-4">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><?=Raptor::get_string('player')?></h3>
            </div>
            <div class="panel-body">
                <p>Player ID: <?= $schar['player']; ?></p>
                <p><a class="btn btn-xs btn-default" href="/admin/player?id=<?= $schar['player']; ?>">Control Panel</a></p>
            </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title"><?=Raptor::get_string('add')?> <?=Raptor::get_string('db_fields')?></h3>
            </div>
            <div class="panel-body">
                <form method='POST'>
                    <p><input type='text' name='name'></p>
                    <p><button name="make" value="1" type="submit" value="1" class="btn btn-xs btn-default"><?=Raptor::get_string('add')?></button></p>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title"><?=Raptor::get_string('notices')?></h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <form method="POST">
                        <textarea name="notes" class="form-control" rows="3"><?= $schar['notes']; ?></textarea> <br>
                        <button type="submit" class="btn btn-xs btn-default"><?=Raptor::get_string('save')?></button>
                    </form>
					</div>
            </div>
        </div>
		<div class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title"><?=Raptor::get_string('inventory')?></h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <?php
						$inv_params = Raptor::ModConfig('inv_params');
						foreach($obj->inv->getItems() as $key => $value) 
						{
							if(!is_array($value)) { continue; }
							echo "<p id='". $key ."'><h4><img src='". $value['image'] ."' width=50 height=50>". $value['name'] ." (". $value['count'] ." шт.)</h4><b><button onclick='giveItem(\"". $key ."\", 1);'>[+1]</button><button onclick='giveItem(\"". $key ."\", ". $value['count'] .");'>[+". $value['count'] ."]</button><button onclick='takeItem(\"". $key ."\", 1);'>[-1]</button><button onclick='takeItem(\"". $key ."\", ". $value['count'] .");'>[-". $value['count'] ."]</button></b><br>";
							$id = $key;
							foreach ($inv_params as $skey => $svalue) 
							{
								if(!strstr($skey, "p_")) { continue; }
								echo "<i>". $svalue['name'] ."</i>: ". $obj->inv->getParam($skey, $key) ."<br>";
							}
							echo "</p>";
						}
					?>
					<hr>

					<form method="GET">
						<select name="give" class="form-control">
							<?php
							foreach (Raptor::ModConfig('inventory') as $key => $value) 
							{
								if (!is_array($value)) 
								{
									continue;
								}
								echo '<option value="'. $key .'">'. $value['name'] .'</option>';
							}
							?>
						</select>
						<input type='text' value='1' name='cnt'>
						<input type='hidden' name='id' value='<?=$_GET['id']?>'>
						<button type="submit" class="btn btn-xs btn-default"><?=Raptor::get_string('give')?></button>
					</form>
                </div>
            </div>
        </div>
    </div>