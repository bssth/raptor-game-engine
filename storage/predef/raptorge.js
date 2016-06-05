var Game = {};

Game.cache = {};
Game.events = {};

$( document ).ready(function() {
    Game.init_chat();
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
		return true;
	}
	return false;
}

Game.send_message = function(message)
{
	return false; // todo
}

Game.get_message = function(author, message)
{
	if($('#chat-messages').length >= 1)
	{
		$('#chat-messages')[0].innerHTML += Game.cache.chat_message.replace('%NICK%', author).replace('%MESSAGE%', message);
		if( $('#chat-messages')[0].children.length >= 10 )
		{
			$('#chat-messages')[0].children[0].remove();
		}
		return true;
	}
	return false;
}