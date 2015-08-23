<?php
if (isset($_GET['name'])) 
{
    $char = Database::GetOne("characters", array("name" => $_GET['name']));
    if (isset($char['_id'])) 
	{
        echo '<div class="alert alert-success"><strong>Персонаж найден</strong><br>';
        foreach ($char as $key => $value) 
		{
            echo "<p>" . $key . " = " . $value . "</p>";
        }
        echo '<p><a href="/admin/char?id=' . $char['_id'] . '">Открыть управление персонажем</a></p>
            </div>';
    } 
	else 
	{
        echo '<div class="alert alert-danger">Персонаж не найден</div>';
    }

    $player = Database::GetOne("players", array("login" => $_GET['name']));
    if (isset($player['_id'])) 
	{
        echo '<div class="alert alert-success"><strong>Игрок найден</strong><br>';
        foreach ($player as $key => $value) 
		{
            echo "<p>" . $key . " = " . $value . "</p>";
		}
        echo '<p><a href="/admin/player?id=' . $player['_id'] . '">Открыть управление игроком</a></p>
            </div>';
    } 
	else 
	{
        echo '<div class="alert alert-danger">Игрок не найден</div>';
    }
}
?>
<form role="form" method="GET">
    <div class="form-group input-group">
        <input class="form-control" value="<?= $_GET['name']; ?>" name="name" type="text">
        <span class="input-group-btn"><button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button></span>
    </div>
    <button type="submit" class="btn btn-default">Поиск</button>
</form>