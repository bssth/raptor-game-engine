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
	$loc = Database::GetOne("config", array("mod" => "locations"))[$char->map];
?>
*/

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
		"graphic": "<?=$char->skin;?>"
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
		
		var last_pos = {};
		
		interpreter.assignCommands(<?=@rpgjs_getcmd('onrun');?>);

		interpreter.execCommands();
		
		RPGJS.Player.speed = 8;
		
		$.get('/api', {'a': 'getposition'}, function(answer){
			answer = eval('(' + answer + ')');
			RPGJS.Player.x = parseInt(answer.x);
			RPGJS.Player.y = parseInt(answer.y);
			RPGJS.Player.moveDir(answer.dir);
			RPGJS.RaptorMap = answer.loc;
			last_pos.x = parseInt(answer.x);
			last_pos.y = parseInt(answer.y);
			console.log('Teleporting character using coords from database (result: ' + answer.answer + ', x & y - ' + answer.x + ' & ' + answer.y + ' )');

			setInterval(function() {
				if(RPGJS.Player.x < 0 || RPGJS.Player.y < 0) {
					RPGJS.Player.x = last_pos.x;
					RPGJS.Player.y = last_pos.y;
					console.log('Position error; X\Y cannot be less than zero');
				}
				$.get('/api', {'a': 'events'}, function(ans){
					if(ans.length > 2) {
						console.log('Evaluate code: ' + ans);
						eval(ans);
					}
				}, "text");
				if(last_pos.x != RPGJS.Player.x || last_pos.y != RPGJS.Player.y) {
					// @todo Good anticheat
					if( Math.abs(RPGJS.Player.x - last_pos.x) > 200 || Math.abs(RPGJS.Player.y - last_pos.y) > 200 ) {
						RPGJS.Player.x = last_pos.x;
						RPGJS.Player.y = last_pos.y;
						console.log('Cheating attempt');
					}
					$.get('/api', {'a': 'teleport','dir':RPGJS.Player.direction,'x': parseInt(RPGJS.Player.x), 'y': parseInt(RPGJS.Player.y)}, function(ans){
						console.log('Writing position to database (result: ' + ans + ')');
					}, "text");
					last_pos.x = RPGJS.Player.x;
					last_pos.y = RPGJS.Player.y;
					RPGJS_Exec(<?=@rpgjs_getcmd('onsync');?>);
				}
				$.get('/api', {'a': 'mapchars', 'map': RPGJS.RaptorMap}, function(answer) {
					answer = eval('(' + answer + ')');
					$.each(answer, function(key, array) { 
						if(!RPGJS.RaptorPlayers[key]) {
							RPGJS.RaptorPlayers[key] = RPGJS.Map.createEvent("EV-1", 0, 0);
							RPGJS.RaptorPlayers[key].addPage({
								"trigger": "player_"+key,
								"type": "fixed",
								"graphic": parseInt(array.skin),
							}, <?=@rpgjs_getcmd('onshake');?>);
							RPGJS.RaptorPlayers[key].display();
							RPGJS.RaptorPlayers[key].char_name = array.name;
							RPGJS.RaptorPlayers[key].char_id = key;
						}
						if(RPGJS.RaptorPlayers[key].x != array.x || RPGJS.RaptorPlayers[key].y != array.y) {
							RPGJS.RaptorPlayers[key].x = array.x;
							RPGJS.RaptorPlayers[key].y = array.y;
							RPGJS.RaptorPlayers[key].moveRoute([array.dir]);
							console.log("Refresh info of " + array.name);
						}
					})
				}, "text");
				
			}, 1500);

		}, "text");
	});
	
});
