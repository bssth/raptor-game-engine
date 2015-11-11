<?php

/*
	** @comment Класс для работы с инвентарём персонажа. Экземпляр хранится в переменной inv класса Char
	** @last_edit 11.10.2015 by Mike
*/

class CharacterInventory 
{

    var $id;
    var $inv;
    var $conf;
    protected $tosave = false;

    function __construct($id = false, $inv = false, $conf = false)
    {
        if ($id === false) 
		{
            $id = $_SESSION['cid'];
        }
        $this->id = $id;
        if ($inv === false) 
		{
            $temp = Database::GetOne('characters', array('_id' => toId($id)));
            $this->inv = isset($temp['inv']) ? (array)$temp['inv'] : array();
        }
        if ($conf === false) 
		{
            $this->conf = Database::GetOne('config', array('mod' => 'inventory'));
        }
    }

    function save()
    {
        return Database::Edit("characters", array('_id' => toId($this->id)), array("inv" => $this->inv));
    }

    function __destruct()
    {
        if ($this->tosave === true) 
		{
            $this->save();
        }
    }

	function refresh()
    {
        $this->inv = Database::GetOne('characters', array('_id' => toId($this->id)))['inv'];
    }

    function giveItem($id, $count)
    {
		$this->tosave = true;
        if ($this->inv[$id]) 
		{
            $this->inv[$id] += $count;
			return true;
        } 
		else 
		{
            $this->inv[$id] = $count;
			return false;
        }
    }

    function takeItem($id, $count)
    {
        $this->tosave = true;
        if ($this->inv[$id] and ($this->inv[$id]-$count)>=0) 
		{
            $this->inv[$id] -= $count;
            return true;
        } 
		else 
		{
            $this->inv[$id] = 0;
            return false;
        }
    }

    function itemsCount($id)
    {
        if ($this->inv[$id]) 
		{
            return $this->inv[$id];
        } 
		else
		{
            return 0;
        }
    }

    function getItems()
    {
        $array = array();
        foreach ($this->inv as $key => $count) 
		{
            $array[$key] = $this->conf[$key];
            $array[$key]['count'] = $count;
        }
        return $array;
    }

    function rpgjs_items($json = false)
    {
        $array = array();
        foreach ($this->inv as $key => $count) 
		{
            $array[$key]['name'] = $this->conf[$key]['name'];
            $array[$key]['image'] = $this->conf[$key]['image'];
            $array[$key]['equip_image'] = $this->conf[$key]['equip_image'];
        }
        if ($json === true) 
		{
            return json_encode($array);
        } 
		else
		{
            return $array;
        }
    }

    function getInv()
    {
        return $this->inv;
    }

    function getParam($pname, $id)
    {
        $param = Database::GetOne("config", array("mod" => "inv_params"))[$pname];
        if (!is_array($param)) 
		{
            raptor_warning("Object as array (" . __METHOD__ . "->" . $pname . ")");
            return false;
        }
        if (empty($param['type'])) 
		{
            return $this->conf[$id][$pname];
        }
        switch ($param['type'])
        {
            case "script":
                $char = new Char();
                $inv = $this;
                return eval($param['script']);
                break;
            case "id":
                return new Char($id);
                break;
            case "int":
                return (int) $this->conf[$id][$pname];
                break;
            case "float":
                return (float) $this->conf[$id][$pname];
                break;
            default:
                return $this->conf[$id][$pname];
                break;
        }
    }

}
