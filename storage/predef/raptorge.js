var Game = {};

Game.cache = {}; // storage for cache
Game.events = {}; // storage for events can be invoked
Game.chat_limit = 13; // count of messages after previous will be deleted

$( document ).ready(function() {
    Game.init_chat();
	Game.online_polling();
});

Game.modal = function(url) {
	if($('#modal-iframe').length >= 1)
	{
		$('#modal-iframe')[0].src = url;
		console.log('In-modal iframe url: ' + url);
		return true;
	}
	else
	{
		return false;
	}
}

Game.init_chat = function()
{
	if($('#chat-messages').length >= 1)
	{
		Game.cache.chat_message = $('#chat-messages')[0].innerHTML;
		$('#chat-messages')[0].innerHTML = '';
		
		for(i = 0; i < (Game.chat_limit-1); i++)
		{
			Game.get_message('Server', '');
		}
		Game.get_message('Server', 'Подключаемся к серверу чата (нет)');
		return true;
	}
	return false;
}

Game.apply_online = function() 
{
	
}

Game.online_polling = function() 
{
	/*if(!Game.cache.online) {
		Game.cache.online = {};
		Game.cache.onlinepoll = JSON.stringify([]);
	}
	console.log("Started polling online...");
	
	$.get('/api/char.onlinepolling', {'list': Game.cache.onlinepoll}, function(data) {
		console.log(data);
		
		json = JSON.parse(data);
		if(json.error) {
			setTimeout(function() { Game.online_polling(); }, 2000);
			return false;
		}
		Game.cache.onlinepoll = data;
		Game.cache.online = {};
		
		$.each(json, function(k, v) {
			Game.cache.online[k] = k;
		});
		
		setTimeout(function() { Game.online_polling(); }, 1200);
	}, 'text');
	*/
	return true;
}

Game.send_message = function(message)
{
	return false; // todo
}

Game.send_event = function(act) {
	$.get('/api/char.event', {'act': act}, function(data) {
		console.log(data);
	});
	return true;
}

Game.char_click = function(target, action)
{
	$.getJSON('/api/char.click', {'action': action, 'char': target}, function(ans) {
		console.log(ans);
	});
	return true;
}

Game.get_message = function(author, message)
{	
	if($('#chat-messages').length >= 1)
	{
		$('#chat-messages')[0].innerHTML += Game.cache.chat_message.replace('%NICK%', author).replace('%MESSAGE%', message);
		if( $('#chat-messages')[0].children.length >= Game.chat_limit )
		{
			$('#chat-messages')[0].children[0].remove();
		}
		return true;
	}
	return false;
}