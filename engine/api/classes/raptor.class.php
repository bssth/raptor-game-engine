<?php
	
	class Raptor 
	{
		public static function AntiHack()
		{
			if (!defined("WEBSITE")) 
			{
				die("Hacking attempt");
			}
			else
			{
				return false;
			}
		}
		
		public static function Debug($status = false)
		{
			$GLOBALS['debug'] = is_bool($status) ? $status : false;
			return $GLOBALS['debug'];
		}
		
		public static function Redirect($url)
		{
			header("Location: " . $url);
			die();
		}
		
		public static function ModConfig($mod)
		{
			if(is_array(Cache::get("config_" . $mod))) 
			{
				return Cache::get("config_" . $mod);
			}
			$arr = Database::GetOne("config", array("mod" => $mod));
			Cache::set("config_" . $mod, $arr, 3600);
			return $arr;
		}
		
		public static function SetModConfig($mod, $config)
		{
			Cache::set("config_" . $mod, $config, 3600);
			return Database::Edit("config", array("mod" => $mod), $config);
		}
		
		public static function SetConfig($config)
		{
			Cache::set("config_main", $config, 3600);
			return Database::Edit("config", array("active" => "1"), $config);
		}
		
		public static function Config($die = false, $merge = true)
		{
			if(is_array(Cache::get("config_main")) or is_string(Cache::get("config_main"))) 
			{
				if($merge === true)
				{
					$GLOBALS = array_merge($GLOBALS, Cache::get("config_main"));
				}
				return Cache::get("config_main");
			}

			else 
			{
				$cursor = Database::GetOne("config", array("active" => '1'));
				Cache::set("config_main", $cursor, 3600);
				if($merge === true and is_array($cursor))
				{
					$GLOBALS = array_merge($GLOBALS, $cursor);
				}
				return $cursor;
			}

			if (empty($cursor['active']) and file_exists(CACHE_ROOT . SEPARATOR . "installed.cache")) 
			{
				if($die === true and $GLOBALS['debug'] == false) { die("Cannot load configuration"); }
				else { return false; }
			}
		}
		
		public static function InitModules($modules)
		{
			if(!is_array($modules)) { $modules = self::Config()['modules']; }
			return Modules::i()->InitModules($modules);
		}
		
		public static function Launcher()
		{
			if (isset($_SESSION['cid'])) 
			{
				if (is_object($_SESSION['cid'])) 
				{
					$_SESSION['cid'] = __toString($_SESSION['cid']);
				}
				$GLOBALS['chars'][$_SESSION['cid']] = new Char($_SESSION['cid']);
				$GLOBALS['chars'][$_SESSION['cid']]->setOnline();
				$ev = check_player_events($_SESSION['cid'], false, true);
				if(!empty($ev['eval'])) 
				{
					eval(implode(" ", $ev['eval']));
				}
			}
			if (isset($_SESSION['id'])) 
			{
				if (is_object($_SESSION['id'])) 
				{
					$_SESSION['id'] = __toString($_SESSION['id']);
				}
				global $player;
				$player = new Player($_SESSION['id']);
			}
			eval(getScript('main'));
			call_user_func("scriptEngineInit");
			checkTimers();
		}
		
		public static function Session()
		{
			new Sessions;
			@session_start();
			return true;
		}
		
		public static function ErrorHandler($errno, $errstr, $errfile, $errline)
		{
			// Deprecated: Database::Insert("errors", array("text" => $errstr, "date" => raptor_date(), "file" => $errfile, "line" => $errline));
			log_error("[$errno] $errstr (file: $errfile, line $errline) \n");
			if (defined("HIDE_ERRORS")) 
			{
				return false;
			}

			switch ($errno)
			{
				case E_USER_ERROR:
					echo "<b>ERROR</b> [$errno] $errstr<br />\n";
					echo "  Фатальная ошибка в строке $errline файла $errfile";
					echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
					raptor_error($errstr, false);
					exit(1);
					break;
				case E_USER_WARNING:
					echo "<b>WARNING</b> [$errno] $errstr<br />\n";
					raptor_warning($errstr, false);
					break;
				case E_WARNING:
					echo "<b>WARNING</b> [$errno] $errstr<br />\n";
					raptor_warning($errstr, false);
					break;
				case E_NOTICE:
					echo "<b>NOTICE</b> [$errno] $errstr<br />\n";
					raptor_notice($errstr, false);
					break;
				case E_USER_NOTICE:
					echo "<b>NOTICE</b> [$errno] $errstr<br />\n";
					raptor_notice($errstr, false);
					break;
				default:
					echo "Error $errno: $errstr<br />\n";
					break;
			}
			return true;
		}
	}