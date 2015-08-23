<?php

/*
	** @comment Вебсокет-сервер, который пришёл на замену AJAX
	** @comment Обрабатывает запросы нормально, тестировалось на Debian 7
	** @todo Кроссплатформенность (+ Windows, Mac), эстетичный вид
	** @last_edit 20.07.2015, 
	** @last_autor Mike
*/

define("MODE", "dev");
error_reporting( E_ALL );

set_time_limit(0);
ob_implicit_flush();
ignore_user_abort(true);
define("NOT_CLIENT_USE", true);
define("WEBSITE", true);

require(__DIR__ . "/../config.php");
require(__DIR__ . "/../api.php");

$baseDir = dirname(__FILE__);

fclose(STDIN);
fclose(STDOUT);
fclose(STDERR);

$STDIN = fopen('/dev/null', 'r');
$STDOUT = fopen($baseDir.'/websocket_action_log.txt', 'ab');
$STDERR = fopen($baseDir.'/websocket_error_log', 'ab');

$GLOBALS['file'] = $baseDir.'/websocket_action_log.txt';
$GLOBALS['connects'] = array();
$GLOBALS['sess_data'] = array();
consolestart();
consolemsg("Trying to start script... "); 

//$timelimit = 0;
$starttime = time();

consolemsg("Starting socket server at " . $GLOBALS['socket_ip'] . ":" . $GLOBALS['socket_port']);
$socket = stream_socket_server("tcp://". $GLOBALS['socket_ip'] .":". $GLOBALS['socket_port'], $errno, $errstr);

if (!$socket) 
{
	consolemsg("ERROR socket unavailable " .$errstr. "(" .$errno. ")");
	consoleend();
    die($errstr. "(" .$errno. ")\n");
}

$connects = array();

while (true) 
{
    $read = $connects;
    $read[] = $socket;
    $write = $except = null;

    if (!stream_select($read, $write, $except, null)) 
	{
        break;
    }

    if (in_array($socket, $read)) 
	{
        if (($connect = stream_socket_accept($socket, -1)) && $info = handshake($connect)) 
		{
			consolemsg("New connection; connect=".$connect.", info=".$info."; OK");            

			$connects[] = $connect;
            onOpen($connect, $info);
        }
        unset($read[ array_search($socket, $read) ]);
    }

    foreach($read as $connect) 
	{
        $data = fread($connect, 100000);

        if (!$data) 
		{
			consolemsg("Connection closed. ");    
			fclose($connect);
            unset($connects[ array_search($connect, $connects) ]);
            onClose($connect);
			consolemsg("OK");    
            continue;
        }

        onMessage($connect, $data);

		$f = decode($data); 
		
	}

	if(time() - $starttime > 75) 
	{
		foreach($GLOBALS['sess_data'] as $save) 
		{
			Database::Edit("characters", array('_id' => toId($save['cid'])), array('x' => $save['x'], 'y' => $save['y'], 'dir' => $save['dir']));
		}
		$starttime = time();
	}
}

fclose($socket);
consolemsg("socket - closed");	
consoleend();

//------------------------------------------------------------------------------------------------------------------------------------------------

function handshake($connect) //Функция рукопожатия
{ 
    $info = array();

    $line = fgets($connect);
    $header = explode(' ', $line);
    $info['method'] = $header[0];
    $info['uri'] = $header[1];

    while ($line = rtrim(fgets($connect))) 
	{
        if (preg_match('/\A(\S+): (.*)\z/', $line, $matches)) 
		{
            $info[$matches[1]] = $matches[2];
        } 
		else
		{
            break;
        }
    }

    $address = explode(':', stream_socket_get_name($connect, true)); //получаем адрес клиента
    $info['ip'] = $address[0];
    $info['port'] = $address[1];

    if (empty($info['Sec-WebSocket-Key'])) 
	{
        return false;
    }

    $SecWebSocketAccept = base64_encode(pack('H*', sha1($info['Sec-WebSocket-Key'] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
    $upgrade = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
        "Upgrade: websocket\r\n" .
        "Connection: Upgrade\r\n" .
        "Sec-WebSocket-Accept:".$SecWebSocketAccept."\r\n\r\n";
    fwrite($connect, $upgrade);

    return $info;
}

function encode($payload, $type = 'text', $masked = false) 
{
    $frameHead = array();
    $payloadLength = strlen($payload);

    switch ($type) 
	{
        case 'text':
            // first byte indicates FIN, Text-Frame (10000001):
            $frameHead[0] = 129;
            break;

        case 'close':
            // first byte indicates FIN, Close Frame(10001000):
            $frameHead[0] = 136;
            break;

        case 'ping':
            // first byte indicates FIN, Ping frame (10001001):
            $frameHead[0] = 137;
            break;

        case 'pong':
            // first byte indicates FIN, Pong frame (10001010):
            $frameHead[0] = 138;
            break;
    }

    // set mask and payload length (using 1, 3 or 9 bytes)
    if ($payloadLength > 65535) 
	{
        $payloadLengthBin = str_split(sprintf('%064b', $payloadLength), 8);
        $frameHead[1] = ($masked === true) ? 255 : 127;
        for ($i = 0; $i < 8; $i++) 
		{
            $frameHead[$i + 2] = bindec($payloadLengthBin[$i]);
        }
        // most significant bit MUST be 0
        if ($frameHead[2] > 127) 
		{
            return array('type' => '', 'payload' => '', 'error' => 'frame too large (1004)');
        }
    } 
	elseif ($payloadLength > 125) 
	{
        $payloadLengthBin = str_split(sprintf('%016b', $payloadLength), 8);
        $frameHead[1] = ($masked === true) ? 254 : 126;
        $frameHead[2] = bindec($payloadLengthBin[0]);
        $frameHead[3] = bindec($payloadLengthBin[1]);
    } 
	else 
	{
        $frameHead[1] = ($masked === true) ? $payloadLength + 128 : $payloadLength;
    }

    // convert frame-head to string:
    foreach (array_keys($frameHead) as $i) 
	{
        $frameHead[$i] = chr($frameHead[$i]);
    }
    if ($masked === true) 
	{
        // generate a random mask:
        $mask = array();
        for ($i = 0; $i < 4; $i++) 
		{
            $mask[$i] = chr(rand(0, 255));
        }

        $frameHead = array_merge($frameHead, $mask);
    }
    $frame = implode('', $frameHead);

    // append payload to frame:
    for ($i = 0; $i < $payloadLength; $i++) 
	{
        $frame .= ($masked === true) ? $payload[$i] ^ $mask[$i % 4] : $payload[$i];
    }

    return $frame;
}

function decode($data)
{
    $unmaskedPayload = '';
    $decodedData = array();

    // estimate frame type:
    $firstByteBinary = sprintf('%08b', ord($data[0]));
    $secondByteBinary = sprintf('%08b', ord($data[1]));
    $opcode = bindec(substr($firstByteBinary, 4, 4));
    $isMasked = ($secondByteBinary[0] == '1') ? true : false;
    $payloadLength = ord($data[1]) & 127;

    // unmasked frame is received:
    if (!$isMasked) 
	{
        return array('type' => '', 'payload' => '', 'error' => 'protocol error (1002)');
    }

    switch ($opcode) 
	{
        // text frame:
        case 1:
            $decodedData['type'] = 'text';
            break;

        case 2:
            $decodedData['type'] = 'binary';
            break;

        // connection close frame:
        case 8:
            $decodedData['type'] = 'close';
            break;

        // ping frame:
        case 9:
            $decodedData['type'] = 'ping';
            break;

        // pong frame:
        case 10:
            $decodedData['type'] = 'pong';
            break;

        default:
            return array('type' => '', 'payload' => '', 'error' => 'unknown opcode (1003)');
    }

    if ($payloadLength === 126) 
	{
        $mask = substr($data, 4, 4);
        $payloadOffset = 8;
        $dataLength = bindec(sprintf('%08b', ord($data[2])) . sprintf('%08b', ord($data[3]))) + $payloadOffset;
    }
	elseif ($payloadLength === 127)
	{
        $mask = substr($data, 10, 4);
        $payloadOffset = 14;
        $tmp = '';
        for ($i = 0; $i < 8; $i++) 
		{
            $tmp .= sprintf('%08b', ord($data[$i + 2]));
        }
        $dataLength = bindec($tmp) + $payloadOffset;
        unset($tmp);
    } 
	else 
	{
        $mask = substr($data, 2, 4);
        $payloadOffset = 6;
        $dataLength = $payloadLength + $payloadOffset;
    }

    /**
     * We have to check for large frames here. socket_recv cuts at 1024 bytes
     * so if websocket-frame is > 1024 bytes we have to wait until whole
     * data is transferd.
     */
    if (strlen($data) < $dataLength)
	{
        return false;
    }

    if ($isMasked) 
	{
        for ($i = $payloadOffset; $i < $dataLength; $i++) 
		{
            $j = $i - $payloadOffset;
            if (isset($data[$i])) 
			{
                $unmaskedPayload .= $data[$i] ^ $mask[$j % 4];
            }
        }
        $decodedData['payload'] = $unmaskedPayload;
    } 
	else 
	{
        $payloadOffset = $payloadOffset - 4;
        $decodedData['payload'] = substr($data, $payloadOffset);
    }

    return $decodedData;
}

//пользовательские сценарии:

function onOpen($connect, $info) 
{
	consolemsg("Connection opened successful"); 
}

function onClose($connect) 
{
	$save = $GLOBALS['sess_data'][array_search($connect, $GLOBALS['connects'])];
	if(is_array($save)) 
	{
		Database::Edit("characters", array('_id' => toId($save['cid'])), array('x' => $save['x'], 'y' => $save['y'], 'dir' => $save['dir']));
	}
    consolemsg("Connection closed successful");
}

function onMessage($connect, $data) 
{
    $f = explode(";", decode($data)['payload']);
	if(!is_array($f)) 
	{ 
		$f = array($f);
	}
	
	$answer = array();

	if($f[0] == "sid" and isset($f[1])) 
	{ 
		$GLOBALS['connects'][$f[1]] = $connect; 
		$sid = $f[1]; 
		$GLOBALS['sess_data'][$f[1]] = Database::GetOne('sessions', array('sess_id' => $sid))['array'];
		$char_array = Database::GetOne('characters', array('_id' => toId($GLOBALS['sess_data'][$f[1]]['cid'])));
		$GLOBALS['sess_data'][$f[1]]['name'] = $char_array['name'];
		$GLOBALS['sess_data'][$f[1]]['player'] = $char_array['player'];
		$GLOBALS['sess_data'][$f[1]]['x'] = $char_array['x'];
		$GLOBALS['sess_data'][$f[1]]['y'] = $char_array['y'];
		$GLOBALS['sess_data'][$f[1]]['dir'] = $char_array['dir'];
		$GLOBALS['sess_data'][$f[1]]['online'] = $char_array['online'];
		$GLOBALS['sess_data'][$f[1]]['map'] = $char_array['map'];
		$GLOBALS['sess_data'][$f[1]]['skin'] = $char_array['skin'];
		$GLOBALS['sess_data'][$f[1]]['_id'] = $GLOBALS['sess_data'][$f[1]]['cid'];
	}
	elseif($sid = array_search($connect, $GLOBALS['connects'])) 
	{ 
	}
	$answer = apply_ws_query($connect, $sid, $f);

    fwrite($connect, encode(trim(json_encode($answer, JSON_UNESCAPED_UNICODE), "\x0")));
}

function consolestart() 
{
	consolemsg("console - start");
}

function consolemsg($msg) 
{
	$file = null;
	if(!file_exists($GLOBALS['file'])) 
	{
	    $file = fopen($GLOBALS['file'],"w");
	}
	else
	    $file = fopen($GLOBALS['file'],"a");
	
	echo $msg."\r\n";
	fputs ($file, "[<b>".date("Y.m.d-H:i:s")."</b>]". $msg ."<br />\r\n"); 
	fclose($file); 
}

function consoleend() 
{
	consolemsg("--- console - end ---");
}

function sendAll($data, $exclude = false) 
{
	$data = is_array($data) ? trim(json_encode($data, JSON_UNESCAPED_UNICODE), "\x0") : $data;
	
	foreach($GLOBALS['connects'] as $key => $value) 
	{
		if($exclude != false and $value == $exclude) { continue; }
		fwrite($value, encode(trim(json_encode($data, JSON_UNESCAPED_UNICODE), "\x0")));
	}
}

function apply_ws_query($connect, $sess_id, $q) 
{
	if($sess_id != '0' and $GLOBALS['sess_data'][$sess_id]['online'] < time()) 
	{ 
		$GLOBALS['sess_data'][$sess_id]['online'] = time()+120; 
	}
	switch($q[0]) 
	{
		case 'sid':
			return array('type' => 'sid', 'answer' => $sess_id);
			break;
		case "uniqid":
			return array('type' => 'uniqid', 'answer' => uniqid());
		case 'test':
			return array('answer' => '1');
			break;
		case 'teleport':
			$GLOBALS['sess_data'][$sess_id]['x'] = $q[1];
			$GLOBALS['sess_data'][$sess_id]['y'] = $q[2];
			$GLOBALS['sess_data'][$sess_id]['dir'] = $q[3];
			sendAll( array('type'=>'teleport','char'=>$GLOBALS['sess_data'][$sess_id]['cid'],'x'=>$q[1],'y'=>$q[2],'dir'=>$q[3]) );
			return array('type'=>'default','answer' => '1');
			break;
		case 'clientjs':
			return array('type' => 'clientjs', 'answer' => rpgjs_getcmd($q[1]));
			break;
		case 'getposition':
			if(!isset($GLOBALS['sess_data'][$sess_id]['x']) or !isset($GLOBALS['sess_data'][$sess_id]['y']) or !isset($GLOBALS['sess_data'][$sess_id]['dir'])) 
			{
				$GLOBALS['sess_data'][$sess_id]['x'] = char($GLOBALS['sess_data'][$sess_id]['cid'])->pos_x;
				$GLOBALS['sess_data'][$sess_id]['y'] = char($GLOBALS['sess_data'][$sess_id]['cid'])->pos_y;
				$GLOBALS['sess_data'][$sess_id]['dir'] = char($GLOBALS['sess_data'][$sess_id]['cid'])->dir;
				$GLOBALS['sess_data'][$sess_id]['loc'] = char($GLOBALS['sess_data'][$sess_id]['cid'])->map;
			}
			return array('type' => 'getposition', 'x' => $GLOBALS['sess_data'][$sess_id]['x'], 'y' => $GLOBALS['sess_data'][$sess_id]['y'], 'loc' => $GLOBALS['sess_data'][$sess_id]['map'], 'dir' => $GLOBALS['sess_data'][$sess_id]['dir']);
			break;
		case 'online':
			$array = array('type' => 'online');
			foreach($GLOBALS['sess_data'] as $key => $value) 
			{
				if($value['online'] < time()) { continue; }
				$array[$value['_id']]['id'] = $value['_id'];
				$array[$value['_id']]['x'] = $value['x'];
				$array[$value['_id']]['y'] = $value['y'];
				$array[$value['_id']]['dir'] = $value['dir'];
				$array[$value['_id']]['name'] = $value['name'];
				$array[$value['_id']]['online'] = $value['online'];
				$array[$value['_id']]['map'] = $value['map'];
				$array[$value['_id']]['skin'] = $value['skin'];
			}
			return $array;
			break;
		case 'events':
			return array('type' => 'events', 'answer' => implode(" ", array_values(check_player_events($GLOBALS['sess_data'][$sess_id]['cid'], true, true)['js'])));
			break;
		case 'mapchars':
			$array = array('type' => 'mapchars');
			foreach($GLOBALS['sess_data'] as $key => $value) 
			{
				if($value['map'] != $q[1] or $GLOBALS['sess_data'][$sess_id]['cid'] == $value['_id']) { continue; }
				$array[$value['_id']]['id'] = $value['_id'];
				$array[$value['_id']]['x'] = $value['x'];
				$array[$value['_id']]['y'] = $value['y'];
				$array[$value['_id']]['dir'] = $value['dir'];
				$array[$value['_id']]['name'] = $value['name'];
				$array[$value['_id']]['online'] = $value['online'];
				$array[$value['_id']]['map'] = $value['map'];
				$array[$value['_id']]['skin'] = $value['skin'];
			}
			return $array;
			break;
		case 'exists':
			return array('type' => 'default', 'error' => 'Deprecated method. Use API.');
			break;
		default:
			return array('type' => 'default', 'error' => 'Bad query ID ('. $q[0] .')');
			break;
	}
}