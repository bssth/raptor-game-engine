<?php

/*
	@last_edit 22.08.2015
	@last_autor Mike
	@comment API для чата
	@todo Переместить в обычный ExtAPI & WebSockets
*/

class messagerDriver {

    public function actionIndex()
    {
        
    }

    /* public function actionHandler() {
      $db = new MySQL;
      $char = new Char($_SESSION['cid']);

      $last_id = isset($_POST['last_id']) ? (int) $_POST['last_id'] : 0;

      $text = isset($_POST['text']) ? trim($_POST['text']) : '';
      if (!empty($text)) {
      $sth = $db->query('INSERT INTO `chat` (`text`,`user`) VALUES (:text,:user)', array(':text' => $text, ':user' => $char->name));
      }
      $sth = $db->query('SELECT * FROM `chat` WHERE `id` > :last_id ORDER BY `id` DESC LIMIT 20', array(':last_id' => $last_id), 'all');

      echo json_encode(array_reverse($sth));
      } */
    public function actionHandler()
    {
	
        $last_id = isset($_POST['last_id']) ? (int) $_POST['last_id'] : 0;

        $result = array();

        if (!empty($_POST['text'])) 
		{
            $sth = Database::Insert("chat", array('user' => char()->name, 'text' => $_POST['text'], 'date' => time()));
        }

        $sth = Database::Get("chat", array('date' => array('$gt' => $last_id)))->sort(array('date' => 1));
		
        foreach ($sth as $o) 
		{
            $result[] = $o;
        }

        echo json_encode($result);
    }

}
