<?php

/*
	** @last_edit 22.08.2015
	** @last_autor Mike
	** @comment Класс для работы с персонажами. Нужно создавать экземпляр
	** @todo Работа с веб-сокетами, если используются
*/

class Char 
{

    public $id;
    public $inv;

    function __construct($id = false)
    {
        if ($id === false) 
		{
            $id = $_SESSION['cid'];
        }
        $this->id = $id;
        $this->inv = new CharacterInventory($id);
    }

    function get($name)
    {
        return $this->__get($name);
    }

    function set($name)
    {
        return $this->__set($name);
    }

    function __toString()
    {
        return $this->name;
    }

    function __set($name, $value)
    {
        return Database::Edit("characters", array("_id" => toId($this->id)), array($name => $value));
    }

    function __get($name)
    {
        if (strstr($name, "p_")) 
		{
            return $this->getParam($name);
        }
        if (!is_object($this->id)) 
		{
            $array = Database::GetOne("characters", array("_id" => toId($this->id)));
        } 
		else
		{
            $array = Database::GetOne("characters", array("_id" => $this->id));
        }
        return isset($array[$name]) ? $array[$name] : false;
    }

    function isOnline()
    {
        $online = $this->__get("online");
        if ($online > time()) 
		{
            return true;
        } 
		else
		{
            return false;
        }
    }

    function setOnline()
    {
        $online = $this->__get("online");
        if ($online > time()) 
		{
            return false;
        } 
		else 
		{
            $this->__set("online", time() + 600);
            return true;
        }
    }

    function all()
    {
        if (!is_object($this->id)) 
		{
            $array = Database::GetOne("characters", array("_id" => toId($this->id)));
        } 
		else
		{
            $array = Database::GetOne("characters", array("_id" => $this->id));
        }
        return $array;
    }

    function getParam($pname)
    {
        $param = Database::GetOne("config", array("mod" => "params"))[$pname];
        if (!is_array($param)) 
		{
            raptor_warning("Bad parameter for getParam ($pname)");
            return false;
        }
        if (!isset($param['type'])) 
		{
            raptor_warning("Cannot get param type for $pname");
            $c_base = Database::GetOne("characters", array("_id" => toId($this->id)))[$pname];
            $value = isset($c_base[$pname]) ? $c_base[$pname] : $param['def'];
            return $value;
        }
        switch ($param['type'])
        {
            case "script":
                $char = $this;
                return eval($param['script']);
                break;
            case "id":
                $c_base = Database::GetOne("characters", array("_id" => toId($this->id)));
                $value = isset($c_base[$pname]) ? $c_base[$pname] : $param['def'];
                return new Char($value);
                break;
            case "int":
                $c_base = Database::GetOne("characters", array("_id" => toId($this->id)));
                $value = isset($c_base[$pname]) ? $c_base[$pname] : $param['def'];
                return (int) $value;
                break;
            case "str":
                $c_base = Database::GetOne("characters", array("_id" => toId($this->id)));
                $value = isset($c_base[$pname]) ? $c_base[$pname] : $param['def'];
                return (string) $value;
                break;
            case "float":
                $c_base = Database::GetOne("characters", array("_id" => toId($this->id)));
                $value = isset($c_base[$pname]) ? $c_base[$pname] : $param['def'];
                return (float) $value;
                break;
            default:
                raptor_warning("Cannot get param type for $pname");
                $c_base = Database::GetOne("characters", array("_id" => toId($this->id)));
                $value = isset($c_base[$pname]) ? $c_base[$pname] : $param['def'];
                return $value;
                break;
        }
    }

    function giveMoney($count, $currency)
    {
        if (!strstr($currency, "money_")) 
		{
            $currency = "money_" . $currency;
        }
        $new = (int) $this->__get($currency) + $count;
        return Database::Edit("characters", array("_id" => toId($this->id)), array($currency => $new));
    }

    function makeEvent($event)
    {
		$event['char'] = $this->id;
		if((!is_string(Cache::get('events_' . $this->id)) and !is_array(Cache::get('events_' . $this->id))) or Cache::get('events_' . $this->id) == '0') 
		{
			Cache::set('events_' . $this->id, array($event), 0);
		}
		else 
		{
			$orig = Cache::get('events_' . $this->id);
			$orig[] = $event;
			Cache::set('events_' . $this->id, $orig);
		}
        return true;
    }

    function sendAlert($message)
    {
        return $this->makeEvent(array('js' => 'alert("' . $message . '");'));
    }

    function sendMessage($message)
    {
        return $this->makeEvent(array('js' => 'RPGJS_Exec(["SHOW_TEXT: {\'text\': \'' . $message . '\'}"]);'));
    }

    function showDialog($id, $type, $title, $text, $params)
    {
        return $this->makeEvent(array('js' => 'showDialog(' . $id . ', "' . $type . '", "' . $title . '", "' . $text . '", "' . $params . '");'));
    }
	
	function canUseAdmin($driver = '')
	{
		if( $this->admin < 1 and !in_array($driver, $this->perms) and file_exists(CACHE_ROOT . SEPARATOR . "installed.cache") )
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
    function check()
    {
        if (!is_object($this->id)) 
		{
            $array = Database::GetOne("characters", array("_id" => new MongoId($this->id)));
        } 
		else 
		{
            $array = Database::GetOne("characters", array("_id" => $this->id));
        }
        if (!empty($array['_id'])) 
		{
            return true;
        } 
		else
		{
            return false;
		}
    }

    public static function create($data = array())
    {
        $array = Database::GetOne("characters", array("name" => $data['name']));
        $player = isset($data['player']) ? $data['player'] : $_SESSION['id'];
        $player = is_object($player) ? $player->__toString() : $player;
        $cnf = Database::GetOne("config", array("mod" => "auth"));
        $admin = isset($data['admin']) ? $data['admin'] : '0';
        if (empty($data['about'])) 
		{
            $data['about'] = "Этот персонаж предпочел о себе ничего не рассказывать!";
        }
        if (isset($array['name'])) 
		{
            return false;
        } 
		else
		{
            return Database::Insert("characters", array(
                        "name" => $data['name'],
                        "player" => $player,
                        "gender" => $data['gender'],
                        "about" => $data['about'],
                        "admin" => $admin,
                        "money" => '0',
                        "lvl" => '0',
                        "avatar" => '',
                        "money_donate" => '0',
                        "pos_x" => $cnf['start_x'],
                        "pos_y" => $cnf['start_y'],
                        "map" => $cnf['start'],
                        "dir" => $cnf['start_dir'],
                        "skin" => '1'
            ));
        }
    }

    public static function delete($name, $check = true)
    {
        if ($check === true) 
		{
            $array = array("name" => $name, "player" => $_SESSION['id']);
        } 
		else
		{
            $array = array("name" => $name);
        }
		
        if (Database::Remove("characters", $array)) 
		{
            return true;
        } 
		else 
		{
            return 0;
        }
    }
	
	public static function find($crit, $val = '')
	{
		if(is_array($crit))
		{
			return Database::GetOne("characters", $crit);
		}
		else
		{
			return Database::GetOne("characters", array($crit => $val));
		}
	}
	
	public static function findAll($crit, $val = '')
	{
		if(is_array($crit))
		{
			return Database::Get("characters", $crit);
		}
		else
		{
			return Database::Get("characters", array($crit => $val));
		}
	}
	
	public static function merge($crit, $val = '', $val2 = '')
	{
		if(is_array($crit))
		{
			return Database::Edit("characters", $crit, $val);
		}
		else
		{
			return Database::Edit("characters", array($crit => $val), $val2);
		}
	}

}
