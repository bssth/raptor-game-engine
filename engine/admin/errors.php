<?php
if (isset($_GET['clean'])) 
{
	file_put_contents(LOGS_ROOT . SEPARATOR . "errors.log", "");
}
if (isset($_GET['count'])) 
{
    $c = $_GET['count'];
} 
else 
{
    $c = 10;
}

$reports = Database::Get("errors", array())->limit($c);

echo '<p>[<a href="?clean=1">'. Raptor::get_string('clear') .'</a>]</p>';

foreach(explode("\n", file_get_contents(LOGS_ROOT . SEPARATOR . "errors.log")) as $string) 
{
	echo '<p>'. $string .'</p>';
}
?>