<?php
/*
	@last_edit 13.10.2015 by Mike
	@comment User cabinet with character selection
*/

class cabinetDriver {

    function __construct()
    {
        if (isset($_GET['logout'])) 
		{
            Player::logout();
        }
        if (empty($_SESSION['id'])) 
		{
            Raptor::Redirect('/index');
        }
        if (isset($_SESSION['cid'])) 
		{
            Raptor::Redirect('/p');
        }
    }

    function actionIndex()
    {
		if(is_object($_SESSION['id'])) 
		{
			$_SESSION['id'] = __toString($_SESSION['id']);
		}
        $chars = Player::getChars($_SESSION['id']);
        $cabinet_list = '';
        foreach ($chars as $array) 
		{
            $cabinet_list = $cabinet_list . templater("interface/cabinet_lst.tpl", array(
                        "%ID%" => $array['_id'],
                        "%NAME%" => $array['name'],
                        "%LVL%" => isset($array['p_lvl']) ? $array['p_lvl'] : '0',
                        "%MONEY%" => isset($array['money']) ? $array['money'] : '0',
                        "%DONATE_MONEY%" => isset($array['money_donate']) ? $array['money_donate'] : '0'
            ));
        }
        $main = new Templater;
        $plr = new Player;
        $main->import("interface/cabinet.tpl");
        $main->setvar("%URL%", "http://" . $GLOBALS['url']);
        $main->setvar("%STORAGE_TPL_URL%", "/storage/tpl");
        $main->setvar("%CHARS_COUNT%", $chars->count());
        $main->setvar("%YEAR%", date("Y"));
        $main->setvar("%GAME_TITLE%", $GLOBALS['name']);
        $main->setvar("%LIST%", $cabinet_list);
        $main->setvar("%CURRENT_PLAYER%", $plr->login);
        $main->setvar("%STORAGE_STATIC_URL%", "/storage/static");
        $main->renderEcho();
    }

    function actionLogout()
    {
        session_destroy();
        unset($_SESSION['id']);
        unset($_SESSION['cid']);
        Raptor::Redirect('/');
    }

    function actionSelect()
    {
        if (empty($_GET['id'])) 
		{
            raptor_error("Trying to select character in cabinet with no id");
        }
        $check = char($_GET['id']);
        if ($check->player == __toString($_SESSION['id'])) 
		{
			if($check->ban >= time()) 
			{
				die('<script>alert('. Raptor::get_string('acc_ban') .' '. $check->ban_reason .'); location.href = "/";</script>');
			}
			else 
			{
				$_SESSION['cid'] = $_GET['id'];
				die('<script>location.href = "/p";</script>');
			}
        } 
		else
		{
            raptor_error("Bad character id");
			echo '<script>alert("'. Raptor::get_string('select_error') .'");</script>';
        }
    }

    function actionMakechar()
    {

        $error = '';
        if (isset($_POST['name'])) 
		{

            $maxchars = Raptor::ModConfig('auth')['maxchars'];
            $chars = Player::getChars($_SESSION['id'])->count();
            if ($chars >= $maxchars) 
			{
                echo "<h1>". Raptor::get_string('char_limit') ." (" . $maxchars . ")</h1>";
            } 
			else
			{

                $id = Char::create(array(
                            "name" => $_POST['name'],
                            "gender" => $_POST['gender'],
                            "about" => $_POST['about']
                ));

                if ($id != false) 
				{
                    Raptor::Redirect('/');
                } 
				else
				{
                    $error = Raptor::get_string('char_exists');
                }
            }
        }
    }

    public function actionRemove()
    {
        Char::delete($_GET['name'], true);
        Raptor::Redirect('/');
    }

}
