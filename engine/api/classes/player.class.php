<?php

/* 
	** @last_edit 22.08.2015
	** @last_autor Mike
	** @comment Класс для работы с игроками (не путать с персонажами)
	** @todo Более приличный вид
*/


class Player 
{

    public $id;

    function __construct()
    {
//		if(empty($id)) { raptor_error("Trying to select player with no id"); return false; }
        $this->id = toId($_SESSION['id']);
    }

    function __get($name)
    {
        $array = Database::GetOne("players", array("_id" => toId($this->id)));
        return $array[$name];
    }
	
	public static function get($id)
	{
		return Database::GetOne("players", array("_id" => toId($id)));
	}

    public static function register($login, $password, $email)
    {
        if (empty($login)) 
		{
            return false;
        }
        if (empty($password)) 
		{
            return false;
        }
        if (empty($email)) 
		{
            return false;
        }
        $check = Database::GetOne("players", array("login" => $login));
        if (isset($check['login'])) 
		{
            return false;
        }
        $playerid = new MongoId();
        Database::Insert("players", array(
            "login" => $login,
            "password" => md5($password),
            "email" => $email,
            "reg_ip" => $_SERVER['REMOTE_ADDR'],
            "last_ip" => $_SERVER['REMOTE_ADDR'],
            "last_date" => raptor_date(),
            "_id" => $playerid
        ));
        return __toString($playerid);
    }

    public static function logout()
    {
        session_destroy();
    }

    function check()
    {
        $array = Database::GetOne("players", array("_id" => $this->id));
        if (isset($array['_id'])) 
		{
            return true;
        } 
		else 
		{
            return false;
        }
    }
	
	function __set($key, $val)
	{
		return $this::set($this->id, array($key => $val));
	}
	
	public static function set($id, $data)
	{
		return Database::Edit("players", array("_id" => toId($id)), $data);
	}
	
    public static function login($login, $password, $session = true)
    {
        if (empty($login)) 
		{
            raptor_warning("Trying to login player with no login");
        }
        if (empty($password)) 
		{
            raptor_warning("Trying to login player with no password");
        }
        $data = Database::GetOne("config", array("mod" => "auth"))['authType'];
        $check = Database::GetOne("players", array($data => $login, "password" => md5($password)));
        if (empty($check['login'])) {
            return false;
        } 
		else
		{
            if ($session === true) 
			{
                $_SESSION['id'] = $check['_id']->__toString();
            }
            Database::Edit("players", array("_id" => $check['_id']), array("last_ip" => $_SERVER['REMOTE_ADDR'], "last_date" => raptor_date()));
            return __toString($check['_id']);
        }
		call_user_func("onPlayerLogin", $_POST['name']);
    }
	
	public static function getChars($id)
	{
		return Database::Get("characters", array("player" => __toString($id)));
	}
	
	public static function find($crit, $val = '')
	{
		if(is_array($crit))
		{
			return Database::GetOne("players", $crit);
		}
		else
		{
			return Database::GetOne("players", array($crit => $val));
		}
	}
	
	public static function findAll($crit, $val = '')
	{
		if(is_array($crit))
		{
			return Database::Get("players", $crit);
		}
		else
		{
			return Database::Get("players", array($crit => $val));
		}
	}
}
