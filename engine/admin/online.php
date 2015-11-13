<?php
	$chars = Char::online(); 

	foreach($chars as $k => $a) 
	{
		echo '<div class="col-lg-2 text-center"><div class="panel panel-default"><div class="panel-body"><a href="/admin/char?id=' . $a->_id . '">' . $a->name . '</a></div></div></div>';
	}
