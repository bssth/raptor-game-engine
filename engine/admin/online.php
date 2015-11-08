<?php
	$chars = Char::findAll(array('online' => array( '$gt' => time() )))->limit(100); 
	echo '<h3>Online '. $chars->count() .' characters</h3>';
	foreach($chars as $a) 
	{
		echo '<div class="col-lg-2 text-center"><div class="panel panel-default"><div class="panel-body"><a href="/admin/char?id=' . $a['_id'] . '">' . $a['name'] . '</a></div></div></div>';
	}
?>