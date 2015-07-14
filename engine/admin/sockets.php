<script>
"use strict"; //All my JavaScript written in Strict Mode http://ecma262-5.com/ELS5_HTML.htm#Annex_C

(function () {
    // ======== private vars ========
	var socket;

    ////////////////////////////////////////////////////////////////////////////
    var init = function () {
		
		socket = new WebSocket(document.getElementById("sock-addr").value);

		socket.onopen = connectionOpen; 
		socket.onmessage = messageReceived; 
		//socket.onerror = errorOccurred; 
		//socket.onopen = connectionClosed;

        document.getElementById("sock-send-butt").onclick = function () {
            socket.send(document.getElementById("sock-msg").value);
			document.getElementById("sock-info").innerHTML += ("Client: "+document.getElementById("sock-msg").value+"<br />");
        };


        document.getElementById("sock-disc-butt").onclick = function () {
            connectionClose();
        };

        document.getElementById("sock-recon-butt").onclick = function () {
            socket = new WebSocket(document.getElementById("sock-addr").value);
            socket.onopen = connectionOpen;
            socket.onmessage = messageReceived;
        };

    };


	function connectionOpen() {
	   socket.send("Подключение к \""+document.getElementById("sock-addr").value+"\" успешно");
	}

	function messageReceived(e) {
	    console.log("Ответ сервера: " + e.data);
        document.getElementById("sock-info").innerHTML += ("Server: "+e.data+"<br />");
	}

    function connectionClose() {
        socket.close();
        document.getElementById("sock-info").innerHTML += "Соединение закрыто <br />";

    }


    return {
        ////////////////////////////////////////////////////////////////////////////
        // ---- onload event ----
        load : function () {
            window.addEventListener('load', function () {
                init();
            }, false);
        }
    }
})().load();
</script>

Вебсокет-сервер:
<input id="sock-addr" type="text" value="ws://<?=$GLOBALS['socket_ip'];?>:<?=$GLOBALS['socket_port'];?>"><br />
Запрос:
<input id="sock-msg" type="text">

<input id="sock-send-butt" type="button" value="send">
<br />
<br />
<input id="sock-recon-butt" type="button" value="reconnect"><input id="sock-disc-butt" type="button" value="disconnect">
<br />
<br />

<div id="sock-info" style="border: 1px solid"> </div>

</body>
</html>