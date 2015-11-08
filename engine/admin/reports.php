<?php
if (isset($_GET['count'])) 
{
    $c = $_GET['count'];
} 
else 
{
    $c = 10;
}

$reports = Reports::get()->limit($c);

echo Raptor::get_string('per_page') . ': <form action="" method="GET"><input type="text" value="' . $c . '" name="count"><input type="submit" value="Показать"></form><hr>';

foreach ($reports as $r) 
{
    echo '<div class="well"><h3>'. Raptor::get_string('from') .' ' . $r['author'] . '</h3><p>' . $r['message'] . '</p></div>';
}
?>