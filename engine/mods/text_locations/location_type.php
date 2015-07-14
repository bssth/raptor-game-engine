<?php
	if(!isset($GLOBALS['current_loc_info']['map'])) {
		$GLOBALS['to_gui'] = '<h3>Возникла ошибка при получении данных локации</h3>';
	}
	if(isset($_GET['teleport'])) {
		char()->map = $_GET['teleport'];
		die("<script>location.href = '?';</script>");
	}
	
	$GLOBALS['to_gui'] = '';
	parse_str($GLOBALS['current_loc_info']['map'], $info);
	
	$GLOBALS['to_gui'] = '<img src="'. $info['image'] .'"><h4>Перейти: </h4>';
	
	foreach($info as $key => $value) {
		if($key == 'image') { continue; }
		$GLOBALS['to_gui'] .= '<p><li><a href="?teleport='. $value .'">'. $key .'</a></li></p>';
	}