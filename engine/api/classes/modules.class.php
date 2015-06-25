<?php

class Modules {
	
    public $list;
    protected $saveOnDestruct;

    function __construct()
    {
        $this->list = array_values(Database::GetOne("config", array("active" => '1'))['modules']);
        $this->saveOnDestruct = false;
    }

    function getModules()
    {
        return $this->list;
    }

    function enable($mod)
    {
        if (!in_array($mod, $this->list)) {
            $this->list[] = $mod;
        }
        $this->saveOnDestruct = true;
    }

    function disable($mod)
    {
        $key = array_search($mod, $this->list);
        if ($key !== false) {
            unset($this->list[$key]);
        }
        $this->saveOnDestruct = true;
    }

    function save()
    {
        $array = array("modules" => array_values($this->list));
        Database::Edit("config", array("active" => '1'), $array);
    }

    function __destruct()
    {
        if ($this->saveOnDestruct == true) {
            $this->save();
        }
    }

}
