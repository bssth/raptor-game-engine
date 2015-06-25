<?php
header('Content-Type: application/json; charset=utf-8');

function valid_json_encode($array) {
	$array = json_encode($array);
	$array = str_replace('{', '{', $array);
	return $array;
}

if (empty($_GET['a'])) {
    die(valid_json_encode(array('answer' => 'Undefined method')));
}

switch ($_GET['a'])
{
    case "uniqid":
        $answer = uniqid();
        break;
	case "test":
		if(isset($_GET['public_key']) or isset($_GET['private_key'])) {
			if($_GET['public_key']==$GLOBALS['public_key'] and $_GET['private_key']==$GLOBALS['private_key']) {
				$answer = valid_json_encode(array('answer' => '1'));
			}
			else {
				$answer = valid_json_encode(array('answer' => '0'));
			}
		}
		else {
			$answer = valid_json_encode(array('answer' => '1'));
		}
		break;
    case "exists":
        $xchar = Database::GetOne("characters", array("name" => $_GET['name']));
        $answer = valid_json_encode(array('answer' => '0'));
        if (!empty($xchar['_id'])) {
            $answer = valid_json_encode(array('answer' => '1'));
        }
        break;
    case "login":
        $pcc = Database::GetOne("players", array("login" => $_GET['name'], "password" => md5($_GET['password'])));
        $answer = valid_json_encode(array('answer' => '0'));
        if (!empty($pcc['_id'])) {
            $answer = valid_json_encode(array('answer' => '1'));
        }
        break;
    case "getposition":
		$loc = Database::GetOne("config", array('mod' => 'locations'));
        $tchar = Database::GetOne("characters", array('_id' => toId($_SESSION['cid'])));
        $answer = valid_json_encode(array('answer' => '1', 'loc' => $tchar['map'], 'map' => $loc[$tchar['map']]['map'], 'x' => $tchar['pos_x'], 'y' => $tchar['pos_y'], 'dir' => $tchar['dir'], 'skin' => $tchar['skin']));
        break;
	case "online":
		$answer = array();
		$chars = Database::Get("characters", array("map" => (string)$_GET['map'], 'online' => array( '$gt' => time() )) );
		foreach($chars as $c) {
            $c['_id'] = __toString($c['_id']);
            $answer[$c['_id']]['id'] = $c['_id'];
            $answer[$c['_id']]['name'] = $c['name'];
            $answer[$c['_id']]['x'] = $c['pos_x'];
            $answer[$c['_id']]['y'] = $c['pos_y'];
			$answer[$c['_id']]['skin'] = $c['skin'];
			$answer[$c['_id']]['dir'] = $c['dir'];
			$answer[$c['_id']]['online'] = $c['online'];
		}
		$answer = valid_json_encode($answer, JSON_FORCE_OBJECT);
		break;
    case "mapchars":
        $ccchar = Database::Get("characters", array("map" => (string)$_GET['map'], 'online' => array( '$gt' => time() )));
        $answer = array();
        foreach ($ccchar as $c) {
			if(__toString($c['_id']) == $_SESSION['cid']) { continue; }
            $c['_id'] = __toString($c['_id']);
            $answer[$c['_id']]['id'] = $c['_id'];
            $answer[$c['_id']]['name'] = $c['name'];
            $answer[$c['_id']]['x'] = $c['pos_x'];
            $answer[$c['_id']]['y'] = $c['pos_y'];
			$answer[$c['_id']]['skin'] = $c['skin'];
			$answer[$c['_id']]['dir'] = $c['dir'];
        }

        $answer = valid_json_encode($answer, JSON_FORCE_OBJECT);
        break;
	case "call":
		$answer = call_user_func("onClientCall", $_GET['name'], json_decode($_GET['params'], true));
		break;
	case "dialog":
		$answer = call_user_func("onDialogResponse", $_GET['id'], $_GET['answer']);
		break;
    case "teleport":
        if (!isset($_SESSION['cid'])) {
            $answer = valid_json_encode(array('answer' => '0'));
            break;
        }
        if (Database::Edit("characters", array('_id' => toId($_SESSION['cid'])), array('pos_x' => $_GET['x'], 'dir' => $_GET['dir'],'pos_y' => $_GET['y']))) {
            $answer = valid_json_encode(array('answer' => '1', 'new_x' => $_GET['x'], 'new_y' => $_GET['y']));
        } else {
            $answer = valid_json_encode(array('answer' => '0'));
        }
        break;
    case "data":
        $answer = valid_json_encode(array('id' => $GLOBALS['id'], 'version' => $GLOBALS['version'], 'url' => $GLOBALS['url'], 'real_url' => $_SERVER['SERVER_NAME'], 'name' => $GLOBALS['name']));
        break;
    case "clientjs":
        $answer = rpgjs_getcmd($_GET['id']);
        break;
	case "events":
		$answer = implode(" ", check_player_events($_SESSION['cid'], true, true)['js']);
		break;
	case "makereport":
		if(!isset($_POST['text'])) { $answer = json_encode(array('error' => 'Cant read text')); break; }
		if(!isset($_SESSION['cid'])) { $answer = json_encode(array('error' => 'Not logged in')); break; }
		if(!isset($char)) { $char = new Char(); }
		$text = trim($_POST['text']);
		$text = htmlspecialchars($_POST['text']);
		$text = strip_tags($_POST['text']);
		Database::Insert("reports", array("author" => $char->name, "message" => $text, "date" => raptor_date()));
		$answer = json_encode(array('message' => 'Report sent'));
		break;
	case "changeemail":
		if(!isset($_SESSION['id'])) { $answer = json_encode(array('error' => 'Not logged in')); break; }
		Database::Edit("players", array("_id"=>toId($_SESSION['id'])), array("email"=>$_GET['new']));	
		$answer = json_encode(array('message' => 'E-Mail changed'));
		break;
	case "changepass":
		if(!isset($_SESSION['id'])) { $answer = json_encode(array('error' => 'Not logged in')); break; }
		Database::Edit("players", array("_id"=>toId($_SESSION['id'])), array("password"=>md5($_GET['new'])));	
		$answer = json_encode(array('message' => 'Password changed'));
		break;
    default:
		$res = call_user_func("onApiMethodCalled", $_GET['a'], $_GET);
		if($res === false) {
			$answer = json_encode(array('error' => 'Undefined method'));
		}
		break;
}

if (isset($_GET['callback'])) {
    die($_GET['callback'] . '(' . $answer . ');');
} else {
    die(trim($answer));
}
?>