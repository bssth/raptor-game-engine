<?php
if (isset($_GET['count'])) {
    $c = $_GET['count'];
} else {
    $c = 10;
}

$reports = Database::Get("reports", array())->limit($c);

echo 'показывать на страницу: <form action="" method="GET"><input type="text" value="' . $c . '" name="count"><input type="submit" value="Показать"></form><hr>';

foreach ($reports as $r) {
    echo '<div class="well"><h3>от ' . $r['author'] . '</h3><p>' . $r['message'] . '</p></div>';
}
?>