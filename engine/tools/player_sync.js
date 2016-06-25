// Thats just a scratch, a real RAPTOR Sync server will be finished later (Mike Chip)

var io = require('socket.io');
var http = require('http');

console.log('Init server...');
var app = http.createServer();
var io = io.listen(app);

app.listen(8080);

io.sockets.on('connection', function (socket) {	
	socket.on('send', function (data) {
		io.sockets.emit('send', data);
	});
});

console.log("Started dedicated RAPTOR Sync server on port 8080");