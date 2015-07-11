<?php

class Database {
    /*
      private static self::$connection = null;

      private static function connection() {
      if(self::self::$connection == null) {
      return self::self::$connection = new MongoClient('localhost');
      }
      else {
      return self::self::$connection;
      }
      }
     */

    private static $cnt = false;
    private static $conn = null;

    private static function connect()
    {
        if (self::$cnt == false) {
            if (!isset($GLOBALS['database_host'])) {
                self::$conn = new MongoClient('localhost');
            } else {
                self::$conn = new MongoClient("mongodb://" . $GLOBALS['database_user'] . ":" . $GLOBALS['database_password'] . "@" . $GLOBALS['database_host']);
            }
            self::$cnt = true;
        } else {
            return false;
        }
    }

    private static function closeConnection()
    {
        if (self::$cnt == true && self::$conn->close()) {
            return true;
        } else {
            die("Cannot close the connection to database!");
        }
    }

    public static function GetAll($collection, $close = false)
    {
        self::connect();
        $db = self::$conn->$GLOBALS['database'];
        $zcollection = $db->$collection;
        $cursor = $zcollection->find();
        if ($close == true) {
            self::closeConnection();
        }
        return $cursor;
    }

    public static function GetOne($collection, $document = array(), $close = false)
    {
        self::connect();
        $db = self::$conn->$GLOBALS['database'];
        $zcollection = $db->$collection;
        $cursor = $zcollection->findOne($document);
        if ($close == true) {
            self::closeConnection();
        }
        if (!is_array($cursor)) {
            return null;
        }
        return $cursor;
    }

    public static function Get($collection, $document = array(), $close = false)
    {
        self::connect();
        $db = self::$conn->$GLOBALS['database'];
        $zcollection = $db->$collection;
        $cursor = $zcollection->find($document);
        if ($close == true) {
            self::closeConnection();
        }
        return $cursor;
    }

    public static function Save($collection, $find = array(), $document = array(), $close = false)
    {
        # Don't use me. I'm too old
        /* self::connect();
          $db = self::$conn->$GLOBALS['database'];
          $zcollection = $db->$collection;
          $doc = $zcollection->findOne($find);
          $document = array_merge($doc, $document);
          $result = $zcollection->save($document);
          if ($close == true) {
          self::closeConnection();
          } */
        self::Edit($collection, $find, $find, $document, $close);
        return true;
    }

    public static function Edit($collection, $document = array(), $edit = array(), $close = false)
    {
        self::connect();
        $db = self::$conn->$GLOBALS['database'];
        $zcollection = $db->$collection;
		if(is_array($zcollection->findOne($document))) {
			$d = array_merge($document, $zcollection->findOne($document));
			foreach ($edit as $key => $value) {
				if ($d[$key] === $value) {
					continue;
				}
				$d[$key] = $value;
			}
			$zcollection->update($document, $d);
		}
		else {
			$zcollection->update($document, $edit);
			self::Insert($collection, array_merge($document, $edit));
		}
        if ($close == true) {
            self::closeConnection();
        }
        return true;
    }

    public static function Insert($collection, $document = array(), $close = false)
    {
        self::connect();
        $db = self::$conn->$GLOBALS['database'];
        $zcollection = $db->$collection;
        $zcollection->insert($document);
        if ($close == true) {
            self::closeConnection();
        }
        return $document['_id'];
    }

    public static function Remove($collection, $item = array(), $close = false)
    {
        self::connect();
        $db = self::$conn->$GLOBALS['database'];
        $collection = $db->$collection;
        $r = $collection->remove($item);
        if ($close == true) {
            self::closeConnection();
        }
        return true;
    }

    public static function toId($string)
    {
        if (!is_object($string)) {
            return new MongoId($string);
        } else {
            return $string;
        }
    }

}
