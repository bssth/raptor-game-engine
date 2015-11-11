<?php

/*
	** @last_edit 22.08.2015 by Mike
	** @comment Система сессий
*/

class Sessions 
{
    protected $savePath;
    protected $sessionName;

    public function __construct($handle = true) 
	{
        if($handle == true)
		{
			session_set_save_handler(
                array($this, 'open'), array($this, 'close'), array($this, 'read'), array($this, 'write'), array($this, 'destroy'), array($this, 'gc')
			);
		}
    }

    public function open($savePath, $sessionName) 
	{
        $this->savePath = $savePath;
        $this->sessionName = $sessionName;
        return true;
    }

    public function close() 
	{
        return true;
    }

    public function read($id) 
	{
        $data = Database::GetOne("sessions", array("sess_id" => $id));
        if (is_array($data)) 
		{
            Database::Edit("sessions", array("sess_id" => $id), array("sess_id" => $id, "time" => time()));
            return $data['data'];
        }
		else 
		{
            Database::Insert("sessions", array("sess_id" => $id, "time" => time()));
            return "";
        }
    }

    public function write($id, $data) 
	{
        Database::Edit("sessions", array("sess_id" => $id), array("sess_id" => $id, "array" => $_SESSION, "data" => $data, "time" => time()));
        return true;
    }

    public function destroy($id)
	{
        Database::Remove("sessions", array("sess_id" => $id));
        return true;
    }

    public function gc($maxlifetime)
	{
        Database::Remove("sessions", array("time" => array('$lt' => time() - 3600)));
        return true;
    }

}
