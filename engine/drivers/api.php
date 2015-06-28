<?php

class apiDriver {
	function __call($a1, $a2) {
		header('Content-Type: application/json; charset=utf-8');
		if(method_exists("ExtAPI", $_GET['a'])) {
			echo ExtAPI::$_GET['a']($_GET);
		}
		else {
			echo ExtAPI::_undefined_method();
		}
	}
}

?>