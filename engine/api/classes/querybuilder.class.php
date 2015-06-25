<?php

/**
 * Description of Core
 *
 * @author 0paquity
 * @last-edit 13:55 04.06.15
 */
class QueryBuilder {

    public $mode;

    /**
     * Local query variable
     * @var type 
     */
    public $query;

    /**
     * Indicators of primacy of methods
     * @var type 
     */
    private $_select;
    private $_insert;
    private $_update;
    private $_delete;

    /**
     * PDO object
     * 
     * @var type 
     */
    private $db;

    /**
     * Prepared query for return
     * 
     * @var type 
     */
    private $prepared_query;

    /**
     * Connection to db (PDO)
     * 
     * @param type $options
     * @return boolean
     */
    public function __construct($options = null)
    {
        $this->mode = 'prod';

        $config = parse_ini_file('config.ini');
        if ($this->db = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['db'], $config['user'], $config['password'], $options)) {
            return true;
        } else {
            $this->db->errorInfo();
        }
    }

    /**
     * #SELECT statement
     * 
     * @param type $what
     * @return \Initialiser
     */
    public function select($what)
    {
        $this->_select = true;
        $this->query = 'SELECT ' . $what;
        return $this;
    }

    /**
     * 
     * @param type $where
     * @return \Initialiser
     * @throws Exception
     */
    public function where($where)
    {
        if ($this->_select == true || $this->_update == true) {
            $this->query = $this->query . ' WHERE ' . $where;
            return $this;
        } elseif ($this->mode == 'dev') {
            throw new Exception('SELECT must be a first method in the chain');
        } else {
            die();
        }
    }

    /**
     * 
     * @param type $from
     * @return \Initialiser
     * @throws Exception
     */
    public function from($from)
    {
        if ($this->_select == true) {
            $this->query = $this->query . ' FROM `' . $from . '`';
            return $this;
        } elseif ($this->mode == 'dev') {
            throw new Exception('SELECT must be a first method in the chain');
        } else {
            die();
        }
    }

    /**
     * #INSERT statement
     * 
     * @param type $into
     * @return \Initialiser
     * @throws Exception
     */
    public function insert($into)
    {
        if (!empty($into)) {
            $this->insert = true;
            $this->query = 'INSERT INTO `' . $into . '` ';

            return $this;
        } elseif ($this->mode == 'dev') {
            throw new Exception('Unfinished query');
        } else {
            die();
        }
    }

    /**
     * Sets the values of insert query
     * 
     * @param array $params
     * @return \Initialiser
     * @throws Exception
     */
    public function values(array $params)
    {
        if ($this->_insert = true) {
            if (!empty($params)) {
                $this->query = $this->query . '(';
                $i = 0;
                foreach ($params as $key => $value) {
                    $keys[$i] = $key;
                    $values[$i] = $value;
                    $i++;
                }

                for ($x = 0; $x < count($keys); $x++) {
                    $this->query = $this->query . '`' . $keys[$x] . '`,';
                    if ($x + 1 == count($keys)) {
                        $this->query = rtrim($this->query, ',');
                        $this->query = $this->query . ') VALUES (';
                    }
                }

                for ($y = 0; $y < count($values); $y++) {
                    $this->query = $this->query . $values[$y] . ',';
                    if ($y + 1 == count($keys)) {
                        $this->query = rtrim($this->query, ',');
                        $this->query = $this->query . ')';
                    }
                }

                return $this;
            }
        } elseif ($this->mode == 'dev') {
            throw new Exception('INSERT must be the first method in chain');
        } else {
            die();
        }
    }

    /**
     * #UPDATE statement
     * 
     * @param type $what
     * @return \Initialiser
     * @throws Exception
     */
    public function update($what)
    {
        if (!empty($what)) {
            $this->_update = true;
            $this->query = 'UPDATE `' . $what . '` SET ';

            return $this;
        } elseif ($this->mode == 'dev') {
            throw new Exception('Unfinished query');
        } else {
            die();
        }
    }

    /**
     * Sets the values to update
     * 
     * @param array $set
     * @return \Initialiser
     * @throws Exception
     */
    public function set(array $set)
    {
        if ($this->_update == true) {
            if (!empty($set)) {
                $i = 1;
                foreach ($set as $key => $value) {
                    $this->query = $this->query . $key . '=' . $value;
                    if ($i != count($set)) {
                        $this->query = $this->query . ', ';
                    }
                    $i++;
                }

                return $this;
            } elseif ($this->mode == 'dev') {
                throw new Exception('Unfinished query');
            } else {
                die();
            }
        } elseif ($this->mode == 'dev') {
            throw new Exception('UPDATE must be the first method in chain');
        } else {
            die();
        }
    }

    /**
     * #DELETE statement
     * 
     * @param type $table
     * @return \Initialiser
     */
    public function delete($table)
    {
        $this->_delete = true;
        $this->query = 'DELETE FROM `' . $table . '`';

        return $this;
    }

    /** #building Query building and returning results! * */

    /**
     * Prepared query with params
     * 
     * @param array $toBind
     * @return type
     */
    public function bindParams(array $toBind)
    {
        $this->prepared_query = $this->db->prepare($this->query);
        if (!$this->prepared_query->execute($toBind)) {
            echo $this->prepared_query->errorInfo()[2];
        } else {
            return $this->prepared_query;
        }
    }

    /**
     * Prepared query without params
     * 
     * @return type
     */
    public function build()
    {
        $this->prepared_query = $this->db->prepare($this->query);
        $this->prepared_query->execute();

        return $this->prepared_query;
    }

    /**
     * Displays current constructed query
     * 
     * @return \Initialiser
     */
    public function display()
    {
        echo $this->query;
        return $this;
    }

    /**
     * SQL query in the traditional from
     * 
     * @param type $query
     * @param type $params
     * @return type
     */
    public function raw_query($query, $params = null)
    {
        if (!empty($query)) {
            $this->prepared_query = $this->db->prepare($query);
            $this->prepared_query->execute($params);

            return $this->prepared_query;
        } elseif ($this->mode == 'dev') {
            throw new Exception('Empty query');
        } else {
            die();
        }
    }

}
