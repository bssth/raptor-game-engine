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
	$loc = Database::GetOne("config", array("mod" => "locations"))[char()->map];
	
	$context = Database::GetOne("config", array("mod" => "char_actions"));
	foreach($context as $k => $v) {
		if(!is_array($v)) { unset($context[$k]); }
	}
	
	if(!is_numeric($loc['map'])) { $loc['map'] = 1; }
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

// don't forget about sendContext(item, player)
function reloadOnline() {
	$.get('/api', {'a': 'online', 'map': '<?=char()->map;?>'}, function(answer) {
					answer = eval('(' + answer + ')');
					document.getElementById('online-box').innerHTML = '<b>Загрузка...</b>';
					var neww = '';
					$.each(answer, function(key, array) { 
						neww = neww + "<font color='white'><p><img src='/storage/img/icons/male.jpg' width=15 height=15> <a id='plist_"+ array.id +"' onclick='openContext(\""+ array.id +"\")'>"+ array.name +"</p></font></a>";
					})
					document.getElementById('online-box').innerHTML = neww;
	}, "text");
}
/*

*/

function openContext(player) {
	if(document.getElementById("player_" + player)) {
		document.getElementById("player_" + player).style.display = 'block';
	}
	else {
		var context_actions = <?=json_encode($context);?>;
		var html = '<nav id="player_' + player + '" class="context-menu"><ul class="context-menu__items">';
		$.each(context_actions, function(key,action) { 
			html = html + '<li class="context-menu__item"><a href="#" onclick="sendContext(\''+ key +'\', \''+ player +'\')" class="context-menu__link">'+ action.name +'</a></li>';
		});
		document.getElementById("plist_" + player).innerHTML = document.getElementById("plist_" + player).innerHTML + html;
	}
	setTimeout(
		function() {
			document.getElementById("player_" + player).style.display = 'none';
		}, 2000
	);
}

// возвращает cookie если есть или undefined
function getCookie(name) {
	var matches = document.cookie.match(new RegExp(
	  "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
	))
	return matches ? decodeURIComponent(matches[1]) : undefined 
}

// уcтанавливает cookie
function setCookie(name, value, props) {
	props = props || {}
	var exp = props.expires
	if (typeof exp == "number" && exp) {
		var d = new Date()
		d.setTime(d.getTime() + exp*1000)
		exp = props.expires = d
	}
	if(exp && exp.toUTCString) { props.expires = exp.toUTCString() }

	value = encodeURIComponent(value)
	var updatedCookie = name + "=" + value
	for(var propName in props){
		updatedCookie += "; " + propName
		var propValue = props[propName]
		if(propValue !== true){ updatedCookie += "=" + propValue }
	}
	document.cookie = updatedCookie

}

// удаляет cookie
function deleteCookie(name) {
	setCookie(name, null, { expires: -1 })
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

function sendContext(item, player) {
	return $.get( "/api?a=contextmenu&item=" + item + "&target=" + player, function( data ) {
		return data;
	});
}

function RPGJS_Exec(data) {
	var interpreter = Class.New("Interpreter");
	
	interpreter.assignCommands(data);

	interpreter.execCommands();
}
/*function RaptorAjax(query, callback) {
	var res = {};
	$.get('/api', {'a': 'teleport','x': parseInt(RPGJS.Player.x), 'y': parseInt(RPGJS.Player.y)}, function(data){
	res = eval('(' + data + ')');
	console.log('AJAX Query: ' + query);
	}, "text");
	return res;
}*/
$(document).keypress(function (e) { 
	window.socket.send('teleport;'+parseInt(RPGJS.Player.x)+";"+parseInt(RPGJS.Player.y)+";"+RPGJS.Player.direction);
});
$(document).ready(function() { 
	reloadOnline();

	setInterval(function() {
		reloadOnline();
	}, 60000);


if(document.getElementById('gui').nodeName == 'CANVAS') {

	var last_pos = {};
	window.socket = new WebSocket("ws://<?=$GLOBALS['socket_ip'];?>:<?=$GLOBALS['socket_port'];?>");
	window.socket.onopen = function() { 
		console.log('Connected to socket server'); 
		window.socket.send("sid;" + getCookie("PHPSESSID")); 
	}; 
	window.socket.onmessage = function(e) {
		e = JSON.parse(e.data);
		if(typeof e == 'string') { console.log('Cant parse JSON from WebSocket Server. Trying to eval.'); e = eval('('+e+')'); }
		if(typeof e.type == 'undefined') { console.log("WebSocket: Answer type = undefined. What happened?"); }
		switch(e.type) {
			case 'events':
				eval(e.answer);
				break;
			case 'default':
				if(e.answer && e.answer != '1') {
					console.log("WebSocket: Type == 'default', e.answer && e.answer != '1'");
					alert("Ошибка. " + e.error);
				}
				else {
					console.log('Got default answer. OK');
				}
				break;
			case 'sid':
				break;
			case 'teleport':
				if(typeof RPGJS.RaptorPlayers[e.char] != 'undefined') {
					RPGJS.RaptorPlayers[e.char].x = e.x;
					RPGJS.RaptorPlayers[e.char].y = e.y;
					RPGJS.RaptorPlayers[e.char].online = e.online;
					RPGJS.RaptorPlayers[e.char].moveRoute([e.dir]);
					console.log("Refresh info of " + e.name);
				}
				else {
					console.log("Sending mapchars query for map " + RPGJS.RaptorMap);
					window.socket.send('mapchars;'+RPGJS.RaptorMap);
				}
				break;
			case 'mapchars':
				console.log('Got mapchars. Trying to parse.');
				$.each(e, function(key, array) { 
					if(typeof array == 'string' || array instanceof String) {
						console.log('Array is string: ' + array);
						return;
					}
					if(typeof RPGJS.RaptorPlayers[key] == 'undefined') {
						console.log("Spawning player: " + array.name);
						RPGJS.RaptorPlayers[key] = RPGJS.Map.createEvent("EV-1", 0, 0);
						RPGJS.RaptorPlayers[key].addPage({
							"trigger": "player_"+key,
							"type": "fixed",
							"graphic": array.skin,
						}, <?=@rpgjs_getcmd('onshake');?>);
						RPGJS.RaptorPlayers[key].display();
						RPGJS.RaptorPlayers[key].char_name = array.name;
						RPGJS.RaptorPlayers[key].char_id = key;
						RPGJS.RaptorPlayers[key].x = array.x;
						RPGJS.RaptorPlayers[key].y = array.y;
						RPGJS.RaptorPlayers[key].moveRoute([array.dir]);
						console.log("Just spawned: " + array.name);
						
					}
					if(RPGJS.RaptorPlayers[key].x != array.x || RPGJS.RaptorPlayers[key].y != array.y) {
						RPGJS.RaptorPlayers[key].x = array.x;
						RPGJS.RaptorPlayers[key].y = array.y;
						RPGJS.RaptorPlayers[key].moveRoute([array.dir]);
						console.log("Refresh info of " + array.name);
					}
				})
				break;
			case 'getposition':
				RPGJS.Player.x = parseInt(e.x);
				RPGJS.Player.y = parseInt(e.y);
				RPGJS.Player.moveDir(e.dir);
				RPGJS.RaptorMap = e.loc;
				last_pos.x = parseInt(e.x);
				last_pos.y = parseInt(e.y);
				console.log('Teleporting character using coords from database (result: x & y - ' + e.x + ' & ' + e.y + ' )');
				window.socket.send('mapchars;'+e.loc);
				console.log(e.loc + " mapchars. Trying to get...");
				break;
			default:
				console.log('Undefined answer type: ' + e.type);
				console.log(e);
				break;
		}
	}; 
	window.socket.onerror = function() { console.log('socket error'); alert('Ошибка соединения с сервером'); }; 
	window.socket.onclose = function(event) {
	  if (event.wasClean) {
		console.log('Соединение было закрыто');
		alert('Соединение с сервером закрыто');
	  } else {
		console.log('Обрыв соединения');
		alert('Соединение с сервером оборвалось. Обновите страницу.');
	  }
	};

		

	RPGJS.RaptorPlayers = {};

	RPGJS.Materials = {
		"characters": <?=@rpgjs_getcmd('graphic_characters');?>, 
		"tilesets": <?=@rpgjs_getcmd('tilesets');?>,
		"bgms": <?=@rpgjs_getcmd('music');?>,
		"autotiles": <?=@rpgjs_getcmd('autotiles');?>
	};
		
	RPGJS.Database = {
	"actors": {
		"1": {
			"graphic": "<?=char()->skin;?>"
		}
	},
	<?=@rpgjs_getcmd('rpgjs_database');?>
	};
		
	RPGJS.defines({
	<?=@rpgjs_getcmd('defines');?>
	}).ready(function() {

		RPGJS.Player.init({
			actor: 1,
			start: {x: 0, y: 0, id: <?=$loc['map'];?>}
		}); 
		
		
		RPGJS.Scene.map(function() {
			var interpreter = Class.New("Interpreter");
			
			interpreter.assignCommands(<?=@rpgjs_getcmd('onrun');?>);

			interpreter.execCommands();
			
			RPGJS.Player.speed = 8;
			
			setInterval(function() {
				window.socket.send('events');
				if(RPGJS.Player.x < 0 || RPGJS.Player.y < 0) {
					RPGJS.Player.x = last_pos.x;
					RPGJS.Player.y = last_pos.y;
					console.log('Position error; X\Y cannot be less than zero');
				}
				if(last_pos.x != RPGJS.Player.x || last_pos.y != RPGJS.Player.y) {
					console.log('Sending new position to server...');
					window.socket.send('teleport;'+parseInt(RPGJS.Player.x)+";"+parseInt(RPGJS.Player.y)+";"+RPGJS.Player.direction);
					last_pos.x = RPGJS.Player.x;
					last_pos.y = RPGJS.Player.y;
				}
				RPGJS_Exec(<?=@rpgjs_getcmd('onsync');?>);
			}, 1000);

			console.log('Trying to get position...');
			window.socket.send('getposition');
			console.log('GetPosition query sent');
			
		});
		
	});

}
});