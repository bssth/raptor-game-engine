<?php

/*
	** @last_edit 22.08.2015 by Mike
	** @comment Шаблонизатор из нашего набора RAPTOR Tools. Простой и не замысловатый, использует функции template и templater как аналог
	** @todo Разметку - [%foreach%] и пр.
*/

class Templater 
{

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
		if(is_string(Cache::get(sha1($root)))) 
		{
			$this->code = Cache::get(sha1($root));
			return;
		}
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
        foreach ($this->vars as $var => $val) 
		{
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