/*
<?php
# @todo Убрать необходимость в использовании PHP в .js файлах
	define('WEBSITE', 1);
	define('HIDE_ERRORS', 1);
	error_reporting(0);
	require_once(__DIR__ . '/../../engine/config.php');
	require_once(__DIR__ . '/../../engine/api.php');
	if(!isset($_SESSION['cid'])) {
		die("//403 Forbidden");
	}
?>
*/

function ClientCall(name, params) {
	$.get( "/api?a=call&name=" + name + "&params=" + params, function( data ) {
	return data;
	});
}

function DialogResponse(id, answer) {
	$.get( "/api?a=dialog&id=" + id + "&answer=" + answer, function( data ) {
	return data;
	});
}

function reloadOnline() {
	$.get('/api', {'a': 'online', 'map': '<?=char()->map;?>'}, function(answer) {
					answer = eval('(' + answer + ')');
					document.getElementById('online-box').innerHTML = '<b>Загрузка...</b>';
					var neww = '';
					$.each(answer, function(key, array) { 
						neww = neww + "<font color='white'><p><img src='/storage/img/icons/male.jpg' width=15 height=15> "+ array.name +"</p></font>";
					})
					document.getElementById('online-box').innerHTML = neww;
	}, "text");
}

function showDialog(id, type, title, text, params) {
	switch(type) {
	case "JAVASCRIPT_OK_CANCEL":
		var answer = confirm(title + "\n\n" + text);
		DialogResponse(id, answer);
		break;
	case "JAVASCRIPT_INPUT":
		var answer = prompt(title + "\n\n" + text, '');
		DialogResponse(id, answer);
		break;
	case "RPGJS_CHOICE":
		/*
		RPGJS_Exec({
			"CHOICES: ['"+ answer +"', 'Text 2', 'Text 3']",
			"CHOICE_0",
			"CHOICE_1",
			"CHOICE_2",
			"ENDCHOICES"
				
		});
		*/
		//DialogResponse(id, 1);
		break;
	}
}
$(document).ready(function() { 
	reloadOnline();

	setInterval(function() {
		reloadOnline();
	}, 60000);
});