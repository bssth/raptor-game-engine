<?php

class Router {

    public static function Start() {
		if(!isset($_SERVER["REDIRECT_URL"])) {
			$srv = "/";
		}
		else {
			$srv = $_SERVER["REDIRECT_URL"];
		}
        $urlArray = explode("/", $srv);
        if (!empty($urlArray[1])) {
            $dDriver = $urlArray[1];
        } else {
            $dDriver = "index";
        }
        if (!empty($urlArray[2])) {
            $dAction = $urlArray[2];
        } else {
            $dAction = "index";
        }
		$GLOBALS['driver'] = $dDriver;
		$GLOBALS['action'] = $dAction;
		if (!empty($urlArray)) {
            $GLOBALS['link'] = $urlArray;
        } else {
            $GLOBALS['link'] = array(false, $dDriver, $dAction);
        }
        @include_once(ENGINE_ROOT . "/drivers/" . $dDriver . ".php");
        $controllerClassName = $dDriver . "Driver";
        #$dAction = ucfirst($dAction);
        $actionMethod = "action" . $dAction;
		
		if(call_user_func("onRouted", $dDriver, $dAction, $urlArray) === false) {
			return false;
		}

		if(!class_exists($controllerClassName)) { loadclass($controllerClassName); }
		
        if (!class_exists($controllerClassName)) {
            header('HTTP/1.1 404 Not Found');

            if (MODE == 'dev') {
                die("<center style='margin-top:20%;'><h1>Error:</h1> Class: <b>" . $controllerClassName . "</b> not found! Please check your URI <sup>mode: <b>" . MODE . "</b></sup></center>");
            } elseif (MODE == 'production') {
                die("<center style='margin-top:20%;'><h1>404</h1>Page not found</center>");
            }
        }
        $controllerClass = new $controllerClassName;

        if (!method_exists($controllerClass, $actionMethod) and !method_exists($controllerClass, "__call")) {
            header('HTTP/1.1 404 Not Found');
            if (MODE == 'dev') {
                die("<center style='margin-top:20%;'> <h1>Error:</h1> Class: <b>" . $controllerClassName . "</b> exists, but method <b>" . $actionMethod . "</b> does not exist! Please check your URI <sup>mode: <b>" . MODE . "</b></sup></center>");
            } elseif (MODE == 'production') {
                die("<center style='margin-top:20%;'><h1>404</h1>Page not found</center>");
            }
        }

        $controllerClass->$actionMethod();

        return true;
    }

}

?>