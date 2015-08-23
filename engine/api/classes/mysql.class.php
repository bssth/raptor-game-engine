<?php

class MySQL 
{

    private $dbh;

    public function __construct()
    {
        /**
			** Создание постоянного соединения с базой данных
        */
        $this->dbh = new PDO('mysql:host=' . $GLOBALS['mysql_data']['host'] . ';dbname=' . $GLOBALS['mysql_data']['db'], $GLOBALS['mysql_data']['user'], $GLOBALS['mysql_data']['password'], array(
            PDO::ATTR_PERSISTENT => true,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES `utf8`"
        ));
    }

    public function r_query($query)
    {
        return (!empty($query)) ? $this->dbh->query($query) : false;
    }

    public function r_exec($query)
    {
        return (!empty($query)) ? $this->dbh->exec($query) : false;
    }

    public function query($query, $param = array(), $fetchType = 'basic')
    {
        $prep = $this->dbh->prepare($query);
        if (!empty($param)) 
		{
            $prep->execute($param);
        } 
		else 
		{
            $prep->execute();
        }

        switch ($fetchType)
        {
            case 'basic':
                $prep = $prep->fetch();
                break;

            case 'all':
                $prep = $prep->fetchAll();
                break;

            case 'column':
                $prep = $prep->fetchColumn();
                break;

            case 'object':
                $prep = $prep->fetchObject();
                break;
        }
        return $prep;
    }

}
