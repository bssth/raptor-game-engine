<?php

class cabinetDriver {

    function __construct()
    {
        if (isset($_GET['logout'])) {
            Player::logout();
        }
        if (empty($_SESSION['id'])) {
            header("Location: /index");
            die();
        }
        if (isset($_SESSION['cid'])) {
            header("Location: /p");
            die();
        }
    }

    function actionIndex()
    {
		if(is_object($_SESSION['id'])) {
			$_SESSION['id'] = $_SESSION['id']->__toString();
		}
        $chars = Database::Get("characters", array("player" => $_SESSION['id']));
        $cabinet_list = '';
        foreach ($chars as $array) {
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
        header('Location: /');
    }

    function actionSelect()
    {
        if (empty($_GET['id'])) {
            raptor_error("Trying to select character in cabinet with no id");
        }
        $check = Database::GetOne("characters", array("_id" => Database::toId($_GET['id'])));
        if ($check['player'] == $_SESSION['id']) {
			if($check['ban'] >= time()) {
				die('<script>alert("Вы были заблокированы \n\n Причина: '. $check['ban_reason'] .'"); location.href = "/";</script>');
			}
			else {
				$_SESSION['cid'] = $_GET['id'];
				die('<script>location.href = "/p";</script>');
			}
        } else {
            raptor_error("Bad character id");
			echo '<script>alert("Ошибка при выборе персонажа");</script>';
        }
    }

    function actionMakechar()
    {

        $error = '';
        if (isset($_POST['name'])) {

            $maxchars = Database::GetOne("config", array("mod" => "auth"))['maxchars'];
            $chars = Database::Get("characters", array("player" => $_SESSION['id']))->count();
            if ($chars >= $maxchars) {
                echo "Исчерпан лимит персонажей на одного игрока (" . $maxchars . ")";
            } else {

                $id = Char::create(array(
                            "name" => $_POST['name'],
                            "gender" => $_POST['gender'],
                            "about" => $_POST['about']
                ));

                if ($id != false) {
                    header("Location: /");
                } else {
                    $error = "Персонаж уже существует";
                }
            }
        }
    }

    public function actionRemove()
    {
        Char::delete($_GET['name'], true);
        header('Location: /');
    }

}
