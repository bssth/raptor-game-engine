<?php

@session_start();

define("STRING_SEPARATOR", " ");
define("API_DIRECTORY", __DIR__);
define("R_DEFINES", __FILE__);

define("ENGINE_VERSION", "dev-2");
define("RAPTOR_URL", "http://raptor.blockstudio.net");

define("ENGINE_NAME", "Raptor Game Engine");
define("ENGINE_STAMP", ENGINE_NAME . " " . ENGINE_VERSION);
?>