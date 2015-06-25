<?php

class Templater {

    public $code;
    public $vars = array();
    public $tpldir;

    function __construct()
    {
        $this->tpldir = TEMPLATE_ROOT;
    }

    function tplRoot()
    {
        return TEMPLATE_ROOT;
    }

    function import($root)
    {
        $handle = fopen($this->tpldir . "/" . $root, "r");
        $html = fread($handle, filesize($this->tpldir . "/" . $root));
        fclose($handle);
        $this->code = $html;
    }

    function setvar($var, $val)
    {
        $this->vars[$var] = $val;
    }

    function setvars($vars)
    {
        $this->vars = array_merge($this->vars, $vars);
    }

    function delvar($var)
    {
        unset($vars[$var]);
    }

    function clear()
    {
        $this->code = "";
    }

    function setTplDir($dir)
    {
        $this->tpldir = $dir;
    }

    function addToTpl($code)
    {
        $this->code = $this->code . $code;
    }

    function render()
    {
        foreach ($this->vars as $var => $val) {
            $this->code = str_replace($var, $val, $this->code);
        }
        return $this->code;
    }

    function renderEcho()
    {
        echo $this->render();
    }

}

?>