<?php
if (isset($_GET['clean'])) {
	Database::Remove("errors", array());
}
if (isset($_GET['count'])) {
    $c = $_GET['count'];
} else {
    $c = 10;
}

$reports = Database::Get("errors", array())->limit($c);

echo '<p>[<a href="?clean=1">Очистить</a>]</p> показывать на страницу: <form action="" method="GET"><input type="text" value="' . $c . '" name="count"><input type="submit" value="Показать"></form><hr>';

foreach ($reports as $r) {
    echo '<div class="well">' . $r['text'] . '</p></div>';
}
?>