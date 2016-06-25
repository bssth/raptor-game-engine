// RAPTOR Chat Engine

var io = require('socket.io');
var http = require('http');

console.log('Init server...');
var app = http.createServer();
var io = io.listen(app);

app.listen(8581);

io.sockets.on('connection', function (socket) {	
	socket.on('send', function (data) {
		io.sockets.emit('send', data);
	});
});

console.log("Listening chat on port 8581");