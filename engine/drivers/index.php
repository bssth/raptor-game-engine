<?php

class indexDriver {

    function actionIndex() {
        if (isset($_SESSION['id'])) {
            header("Location: /cabinet");
        }
        switch (@$_GET['result']) {
            case 'regerror':
                echo "<script>alert('Введены неверные данные или аккаунт уже существует');</script>";
                break;
            case 'loginerror':
                echo "<script>alert('Неверный логин или пароль');</script>";
                break;
            default:
                break;
        }
        $main = new Templater;
        $main->import("interface/index.tpl");
        $main->setvar("%URL%", "http://" . $GLOBALS['url']);
        $main->setvar("%LOGIN_URL%", "/index/login");
        $main->setvar("%STORAGE_TPL_URL%", "/storage/tpl");
        $main->setvar("%YEAR%", date("Y"));
        $main->setvar("%CSS%", "<style>" . templater("css/main.css", array("%ROOT%" => "/storage/tpl")) . "</style>");
        $main->setvar("%REGISTER%", template("interface/register.tpl"));
		$cursor = Database::Get("news", array('public' => 1))->sort(array('date' => -1))->limit(1)->getNext();
		$newss = templater("interface/news.tpl", array(
			"%SUBJECT%" => $cursor['title'],
			"%DATE%" => $cursor['date'],
			"%ANNOUNCE%" => $cursor['short'],
			"%LINK_MORE%" => "http://" . $GLOBALS['url'] . "/news/read?id=" . $cursor['_id'],
			"%ID%" => $cursor['_id'])
		);
        $main->setvar("%NEWS%", $newss);
        $main->setvar("%GAME_TITLE%", $GLOBALS['name']);
        $main->setvar("%STORAGE_STATIC_URL%", "/storage/static");
        $main->renderEcho();
    }

    function actionRegister() {
        if ((Database::GetOne("config", array("mod" => "auth"))['allowRegister'] == 0) and $_SESSION['invited']!==true) {
            echo "<script>alert('Регистрация закрыта'); window.location = '/';</script>";
            die();
        }
        if ($res = Player::register($_POST['login'], $_POST['password'], $_POST['email'])) {
            $_SESSION['id'] = $res;
			if($_SESSION['invited'] === true) { $_SESSION['invited'] = false; }
			call_user_func("onPlayerRegister", $_POST['login'], $_POST['password'], $_POST['email']);
			@header("Location: /cabinet");
			die("<script>location.href = '/cabinet';</script>");
        } else {
            @header("Location: /index?result=regerror");
			die("<script>location.href = '/index?result=regerror';</script>");
        }
    }

    function actionLogin() {
        if (Player::login($_POST['name'], $_POST['password'], true)) {
			call_user_func("onPlayerLogin", $_POST['name'], $_POST['password'], true);
            @header("Location: /cabinet");
			die("<script>location.href = '/cabinet';</script>");
        } else {
			call_user_func("onPlayerLogin", $_POST['name'], $_POST['password'], false);
            @header("Location: /index?result=loginerror");
			die("<script>location.href = '/index?result=loginerror';</script>");
        }
    }

}
