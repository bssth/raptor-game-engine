<?php
if (empty($_GET['id'])) 
{
    $_GET['id'] = $_SESSION['id'];
}

if (isset($_POST['change'])) 
{
    unset($_POST['change']);
	Player::set($_GET['id'], $_POST);
}
if (isset($_POST['make'])) 
{
	Player::set($_GET['id'], array($_POST['name'] => 0));
}
if (isset($_POST['notes'])) 
{
    Player::set($_GET['id'], array("notes" => $_POST['notes']));
}

$char = Player::get($_GET['id']);

if (empty($char['_id'])) 
{
    echo '<div class="alert alert-danger">Игрок не найден</div>';
    die();
}
?>


<div class="row">
    <div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Поля базы данных</h3>
            </div>
            <div class="panel-body">
				<?php
				foreach ($char as $key => $value) 
				{
					echo "<p>" . $key . " = " . $value . "</p>";
				}
				?>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Изменить поля БД</h3>
            </div>
            <div class="panel-body">
				<?php
				foreach ($char as $key => $value) 
				{
					if ($key == '_id') 
					{
						continue;
					}
					echo "<form method='POST'>"
					. "<p>" . $key . " = <input type='text' value='" . $value . "' name='" . $key . "'>"
					. '<button name="change" type="submit" value="1" class="btn btn-xs btn-default">Изменить</button>'
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
                <h3 class="panel-title">Персонажи</h3>
            </div>
            <div class="panel-body">
                <ul>
                    <?php

						$a1 = Player::getChars($_GET['id']);
						foreach ($a1 as $array) 
						{
							echo '<li><a href="/admin/char?id=' . $array['_id'] . '">' . $array['name'] . '</a></li>';
						}

                    ?>
                </ul>
            </div>
			</div>
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Добавить поле БД</h3>
            </div>
            <div class="panel-body">
                <form method='POST'>
                    <p><input type='text' name='name'></p>
                    <p><button name="make" value="1" type="submit" value="1" class="btn btn-xs btn-default">Добавить</button></p>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title">Заметки</h3>
            </div>
            <div class="panel-body">
                Здесь можно оставить любые заметки по этому персонажу
                <div class="form-group">
                    <form method="POST">
                        <textarea name="notes" class="form-control" rows="3"><?= $char['notes']; ?></textarea> <br>
                        <button type="submit" class="btn btn-xs btn-default">Сохранить</input>
                    </form>
                </div>
            </div>
        </div>
    </div>