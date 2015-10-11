<?php

class ExtAPI 
{
	

    public static function __callStatic($func, $args)
    {
        return $self::_undefined_method();
    }

    public static function _undefined_method($query)
    {
        return json_encode(array('answer' => 'Undefined method', 'query' => $query));
    }

    public static function uniqid($array)
    {
        return uniqid();
    }

    public static function test($array)
    {
        if (isset($array['public_key']) or isset($array['token'])) 
		{
            if ($array['public_key'] == Raptor::Globals('public_key') and $array['token'] == Raptor::Globals('private_key')) 
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
	
	public static function inventory_getitems($array)
	{
		if ($array['token'] != Raptor::Globals('private_key')) 
		{
			return json_encode(array('error' => 'Invalid token'));
        } 
		if(!isset($array['char'])) { return json_encode(array('error' => 'Character not specified')); }
		
		return json_encode( char($array['char'])->inv->getItems(), JSON_UNESCAPED_UNICODE );
	}
	
	public static function inventory_giveitem($array)
	{
		if ($array['token'] != Raptor::Globals('private_key')) 
		{
			return json_encode(array('error' => 'Invalid token'));
        } 
		if(!isset($array['char']) or !isset($array['cnt']) or !isset($array['item'])) { return json_encode(array('error' => 'Data not specified')); }
		
		$res = char($array['char'])->inv->giveItem($array['item'], $array['cnt']);
		return json_encode(array('answer' => '1', 'existed_earlier' => $res));
	}
	
	public static function basepic($array)
	{
		return constant("BasePics::".str_replace('.png', '', $_SERVER['QUERY_STRING']));
	}
	
	public static function parse_string($array)
	{
		BBParser::loadLibrary();
		return BBParser::parseAll($_REQUEST['str']);
	}
	
	public static function debug_call($array)
	{
		if ($array['token'] != Raptor::Globals('private_key') or MODE != 'dev') 
		{
			return json_encode(array('error' => 'Invalid token or not in debug mode'));
        } 
		
		$clname = $array['class'];
		$class = new $clname;
		
		$res = call_user_func_array(array($class, $array['method']), array_values(explode(',', $array['params'])));
		return json_encode(array('answer' => $res));
	}
	
	public static function char($array)
	{
		if ($array['token'] != Raptor::Globals('private_key')) 
		{
			return json_encode(array('error' => 'Invalid token'));
        } 
		
		$act = $array['method'];
		$ex = char($array['id']);
		$res = call_user_func_array(array($ex, $array['method']), array_values(explode(',', $array['params'])));
		return json_encode(array('answer' => $res));
	}
	
	public static function inventory_takeitem($array)
	{
		if ($array['token'] != Raptor::Globals('private_key')) 
		{
			return json_encode(array('error' => 'Invalid token'));
        } 
		if(!isset($array['char']) or !isset($array['cnt']) or !isset($array['item'])) { return json_encode(array('error' => 'Data not specified')); }
		
		$res = char($array['char'])->inv->takeItem($array['item'], $array['cnt']);
		return json_encode(array('answer' => $res));
	}
	
	public static function mail_send($array)
	{
		/*if(Raptor::Globals('private_key') == $array['token'])
		{
			// some actions here
		}
		else
		{
			return json_encode(array('error' => 'Invalid token'));
		}*/
		return json_encode(array('error' => 'Function is disabled'));
	}
	
	public static function dino_handle($array)
	{
		$dino = new Dino;
		return $dino->handleCommand($_SERVER['QUERY_STRING']);
	}
	
	public static function language_get($array)
	{
		$class = new Multilingual($array['lang']);
		if($class->exists() != true)
		{
			return json_encode(array('error' => 'Language not found'));
		}
		else
		{
			return json_encode(array('answer' => $class->$array['str']));
		}
	}
	
	public static function news_get($array)
	{
		$answer = array();
		foreach(News::get(null, $array['limit']) as $key => $value)
		{
			$value['_id'] = __toString($value['_id']);
			$answer[] = $value;
		}
		return is_array($answer) ? json_encode($answer, JSON_UNESCAPED_UNICODE) : json_encode(array());
	}
	
	public static function character_exists($array)
	{
		$data = Char::find('name', $array['name']);
		return json_encode(array('answer' => isset($data['name'])));
	}
	
    public static function exists($array)
    {
        return self::character_exists($array);
    }

	public static function player_register($array)
	{
		return json_encode( array('result' => Player::register($array['login'], $array['password'], $array['email'])) );
	}
	
	public static function player_logout($array)
	{
		Player::logout();
		return json_encode(array());
	}
	
	public static function player_getchars($array)
	{
		if(isset($_SESSION['id']) and !isset($array['id'])) { $array['id'] = $_SESSION['id']; }
		if(Raptor::Globals('private_key') == $array['token'] or $_SESSION['id'] == $array['id'])
		{
			$answer = array();
			foreach(Player::getChars($array['id']) as $key => $value)
			{
				$answer[] = array('id' => __toString($value['_id']), 'name' => $value['name']);
			}
			return is_array($answer) ? json_encode($answer) : json_encode(array());
		}
		return json_encode(array('error' => 'Invalid token'));
	}
	
	public static function login($array) { self::player_login($array); }
	
    public static function player_login($array)
    {
        return json_encode(array('answer' => Player::login($array['login'], $array['password'], true)));
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
        return json_encode(array('id' => Raptor::Globals('id'), 'version' => Raptor::Globals('version'), 'url' => Raptor::Globals('url'), 'real_url' => $_SERVER['SERVER_NAME'], 'name' => Raptor::Globals('name')));
    }

    public static function clientjs($array)
    {
        return rpgjs_getcmd($array['id']);
    }

    public static function events($array)
    {
        return implode(" ", check_player_events($_SESSION['cid'], true, true)['js']);
    }

	public static function reports_add($array) { return self::makereport($array); }
	
	public static function reports_get($array)
	{
		if(Raptor::Globals('private_key') == $array['token'])
		{
			$answer = array();
			foreach(Reports::Get() as $key => $value)
			{
				$answer[] = $value;
			}
			return is_array($answer) ? json_encode($answer) : json_encode(array());
		}
		return json_encode(array('error' => 'Invalid token'));
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
		Reports::add(char()->name, $text);
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
		return call_user_func("onPlayerContextMenu", $array['item'], char($array['target']));
	}
}

?>