<?php
if (isset($_GET['name'])) 
{
    $char = Char::find(array("name" => $_GET['name']));
    if (isset($char['_id'])) 
	{
        echo '<div class="alert alert-success"><strong>'. Raptor::get_string('found') .'</strong><br>';
        foreach ($char as $key => $value) 
		{
            echo "<p>" . $key . " = " . $value . "</p>";
        }
        echo '<p><a href="/admin/char?id=' . $char['_id'] . '">Character Control Panel</a></p>
            </div>';
    } 
	else 
	{
        echo '<div class="alert alert-danger">'. Raptor::get_string('char_not_found') .'</div>';
    }

	$player = Player::find(array("login" => $_GET['name']));
    if (isset($player['_id'])) 
	{
        echo '<div class="alert alert-success"><strong>'. Raptor::get_string('found') .'</strong><br>';
        foreach ($player as $key => $value) 
		{
            echo "<p>" . $key . " = " . $value . "</p>";
		}
        echo '<p><a href="/admin/player?id=' . $player['_id'] . '">Player Control Panel</a></p>
            </div>';
    } 
	else 
	{
        echo '<div class="alert alert-danger">'. Raptor::get_string('player_not_found') .'</div>';
    }
}
?>
<form role="form" method="GET">
    <div class="form-group input-group">
        <input class="form-control" value="<?= $_GET['name']; ?>" name="name" type="text">
        <span class="input-group-btn"><button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button></span>
    </div>
    <button type="submit" class="btn btn-default"><?=Raptor::get_string('find')?></button>
</form>