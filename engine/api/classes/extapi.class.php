<?php

class ExtAPI 
{

    public static function __callStatic($func, $args)
    {
        return $self::_undefined_method();
    }

    public static function _undefined_method()
    {
        return json_encode(array('answer' => 'Undefined method'));
    }

    public static function uniqid($array)
    {
        return uniqid();
    }

    public static function test($array)
    {
        if (isset($array['public_key']) or isset($array['private_key'])) 
		{
            if ($array['public_key'] == $GLOBALS['public_key'] and $array['private_key'] == $GLOBALS['private_key']) 
			{
                $answer = json_encode(array('answer' => '1'));
            } 
			else
			{
                $answer = json_encode(array('answer' => '0'));
            }
        } 
		else 
		{
            $answer = json_encode(array('answer' => '1'));
        }
        return $answer;
    }

    public static function exists($array)
    {
        $xchar = Database::GetOne("characters", array("name" => $array['name']));
        $answer = json_encode(array('answer' => '0'));
        if (!empty($xchar['_id'])) 
		{
            $answer = json_encode(array('answer' => '1'));
        }
        return $answer;
    }

    public static function login($array)
    {
        $pcc = Database::GetOne("players", array("login" => $array['name'], "password" => md5($array['password'])));
        $answer = json_encode(array('answer' => '0'));
        if (!empty($pcc['_id'])) 
		{
            $answer = json_encode(array('answer' => '1'));
        }
        return $answer;
    }

    public static function getposition($array)
    {
        $loc = Database::GetOne("config", array('mod' => 'locations'));
        $tchar = Database::GetOne("characters", array('_id' => toId($_SESSION['cid'])));
        $answer = json_encode(array('answer' => '1', 'loc' => $tchar['map'], 'map' => $loc[$tchar['map']]['map'], 'x' => $tchar['pos_x'], 'y' => $tchar['pos_y'], 'dir' => $tchar['dir'], 'skin' => $tchar['skin']));
        return $answer;
    }

    public static function online($array)
    {
        $answer = array();
        $chars = Database::Get("characters", array("map" => (string) $array['map'], 'online' => array('$gt' => time())));
        foreach ($chars as $c) 
		{
            $c['_id'] = __toString($c['_id']);
            $answer[$c['_id']]['id'] = $c['_id'];
            $answer[$c['_id']]['name'] = $c['name'];
            $answer[$c['_id']]['x'] = $c['pos_x'];
            $answer[$c['_id']]['y'] = $c['pos_y'];
            $answer[$c['_id']]['skin'] = $c['skin'];
            $answer[$c['_id']]['dir'] = $c['dir'];
            $answer[$c['_id']]['online'] = $c['online'];
        }
        $answer = json_encode($answer, JSON_FORCE_OBJECT);
        return $answer;
    }

    public static function mapchars($array)
    {
        $ccchar = Database::Get("characters", array("map" => (string) $array['map'], 'online' => array('$gt' => time())));
        $answer = array();
        foreach ($ccchar as $c) 
		{
            if (__toString($c['_id']) == $_SESSION['cid']) 
			{
                continue;
            }
            $c['_id'] = __toString($c['_id']);
            $answer[$c['_id']]['id'] = $c['_id'];
            $answer[$c['_id']]['name'] = $c['name'];
            $answer[$c['_id']]['x'] = $c['pos_x'];
            $answer[$c['_id']]['y'] = $c['pos_y'];
            $answer[$c['_id']]['skin'] = $c['skin'];
            $answer[$c['_id']]['dir'] = $c['dir'];
        }

        $answer = json_encode($answer, JSON_FORCE_OBJECT);
        return $answer;
    }

    public static function call($array)
    {
        return call_user_func("onClientCall", $array['name'], json_decode($array['params'], true));
    }

    public static function dialog($array)
    {
        return call_user_func("onDialogResponse", $array['id'], $array['answer']);
    }
	
    public static function teleport($array)
    {
        if (!isset($_SESSION['cid'])) 
		{
            $answer = json_encode(array('answer' => '0'));
        }
        if (Database::Edit("characters", array('_id' => toId($_SESSION['cid'])), array('pos_x' => $array['x'], 'dir' => $array['dir'], 'pos_y' => $array['y']))) 
		{
            $answer = json_encode(array('answer' => '1', 'new_x' => $array['x'], 'new_y' => $array['y']));
        } 
		else
		{
            $answer = json_encode(array('answer' => '0'));
        }
        return $answer;
    }

    public static function data($array)
    {
        return json_encode(array('id' => $GLOBALS['id'], 'version' => $GLOBALS['version'], 'url' => $GLOBALS['url'], 'real_url' => $_SERVER['SERVER_NAME'], 'name' => $GLOBALS['name']));
    }

    public static function clientjs($array)
    {
        return rpgjs_getcmd($array['id']);
    }

    public static function events($array)
    {
        return implode(" ", check_player_events($_SESSION['cid'], true, true)['js']);
    }

    public static function makereport($array)
    {
        if (!isset($_POST['text'])) 
		{
            $answer = json_encode(array('error' => 'Cant read text'));
        }
        if (!isset($_SESSION['cid'])) 
		{
            $answer = json_encode(array('error' => 'Not logged in'));
        }
        $text = trim($_POST['text']);
        $text = htmlspecialchars($_POST['text']);
        $text = strip_tags($_POST['text']);
        Database::Insert("reports", array("author" => char()->name, "message" => $text, "date" => raptor_date()));
        $answer = json_encode(array('message' => 'Report sent'));
        return $answer;
    }

    public static function changeemail($array)
    {
        if (!isset($_SESSION['id'])) 
		{
            $answer = json_encode(array('error' => 'Not logged in'));
        }
        Database::Edit("players", array("_id" => toId($_SESSION['id'])), array("email" => $array['new']));
        $answer = json_encode(array('message' => 'E-Mail changed'));
        return $answer;
    }

    public static function changepass($array)
    {
        if (!isset($_SESSION['id'])) 
		{
            $answer = json_encode(array('error' => 'Not logged in'));
        }
        Database::Edit("players", array("_id" => toId($_SESSION['id'])), array("password" => md5($array['new'])));
        $answer = json_encode(array('message' => 'Password changed'));
        return $answer;
    }

    public static function handledino($array)
    {
        $dino = new Dino;
        return $dino->handleCommand($_REQUEST['string']);
    }

	public static function contextmenu($array)
	{
		return call_user_func("onPlayerContextMenu", $array['item'], new Char($array['target']));
	}
}

?>