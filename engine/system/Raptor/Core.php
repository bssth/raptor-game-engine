<?php
	
	/**
	 * Raptor Core
	 *
	 * @version 1.5
	 * @author Mike Chip
	 *
	 */
	 
	namespace Raptor;
	
	use \Raptor\Config as Config;
	
	class Core
	{
		// Core constants
		const SEPARATOR = '/'; // separator for directories; you can use / both in Windows and Unix
		const EXT = '.php'; // scripts extension
		const MODS = 'modules'; // folder with modules..
		const SYS = 'system'; // ..system scripts..
		const HOOKS = 'hooks'; // and hooks
		const CACHE = 'cache';
		
		/**
		 * Inits framework and sets handlers
		 * @return void
		 */
		public static function init()
		{
			if( file_exists(__DIR__ . '/../../config.php') ) {
				require_once(__DIR__ . '/../../config.php');
			}
			else {
				throw new Exception('<h1>Cannot load configuration</h1>');
			}
			
			spl_autoload_register('\Raptor\Core::autoloader'); // register autoloader 
			set_error_handler('\Raptor\Core::error_handler'); // register error handler that throws exception 
			set_exception_handler('\Raptor\Core::exception_handler'); // register exception handler 
			register_shutdown_function('\Raptor\Core::shutdown'); // register shutdown function
			
			session_write_close(); // stop session writing
			
			session_start();
			ignore_user_abort();
			set_time_limit(0);
			
			require_once(\Raptor\Config::ROOT . self::SEPARATOR . self::CACHE . self::SEPARATOR . 'scripts.php');
			\Raptor\EventListener::invoke('ready'); // invoke event when script is ready
		}
		
		/**
		 * Exception handler 
		 */
		public static function exception_handler($e)
		{
			if(Config::debug === true) {
				die(nl2br($e->__toString()));
			}
			else {
				die((new \Raptor\Templater('error_500'))->render());
			}
		}
		
		/**
		 * Web errors handler 
		 */
		public static function web_error($num)
		{
			die((new \Raptor\Templater('error_' . $num))->render());
			return null;
		}
		
		/**
		 * Error handler
		 */
		public static function error_handler($errno, $errstr, $errfile, $errline, $errcontext)
		{
			throw new Exception( "Error #{$errno}: {$errstr} [File {$errfile} in line {$errline}]" ); 
		}
		
		/**
		 * Class autoloader
		 *
		 * @param string $class
		 * @return void
		 */
		public static function autoloader($class)
		{
			if(class_exists($class, false)) { 
				return null; 
			}
			
			self::hooks_autoloader($class);
			self::modules_autoloader($class);
			self::system_autoloader($class);
		}
		
		/**
		 * Hooks loader
		 *
		 * @param string $class
		 * @return void
		 *
		 */
		protected static function hooks_autoloader($class)
		{
			if(class_exists($class, false) or !file_exists(\Raptor\Config::ROOT . self::SEPARATOR . self::HOOKS . self::SEPARATOR . str_replace('\\', '/', $class) . self::EXT)) { return; }
			
			require_once(\Raptor\Config::ROOT . self::SEPARATOR . self::HOOKS . self::SEPARATOR . str_replace('\\', '/', $class) . self::EXT);
		}
		
		/**
		 * System classes loader
		 *
		 * @param string $class
		 * @return void
		 *
		 */
		protected static function system_autoloader($class)
		{
			if(class_exists($class, false) or !file_exists(\Raptor\Config::ROOT . self::SEPARATOR . self::SYS . self::SEPARATOR . str_replace('\\', '/', $class) . self::EXT)) { return; }
			
			require_once(\Raptor\Config::ROOT . self::SEPARATOR . self::SYS . self::SEPARATOR . str_replace('\\', '/', $class) . self::EXT);
		}
		
		/**
		 * Start as web application
		 */
		public static function web_init()
		{
			$uri = (isset($_SERVER['REQUEST_URI']) and strlen($_SERVER['REQUEST_URI']) > 1) ? substr($_SERVER['REQUEST_URI'], 1) : 'index/index';

			if(strpos($uri, '?') != false and strpos($uri, '?') >= 0) { 
				$uri = trim(strstr($uri, '?', true), '?'); 
			}
			
			$route = explode('/', $uri);
			
			$driver = '\\Controller\\' . ucfirst(isset($route[0]) ? $route[0] : 'Index');
			$action = 'action' . ucfirst(isset($route[1]) ? $route[1] : 'Index');
			
			if(!class_exists($driver)) {
				self::web_error(404);
			}
			
			$class = new $driver;
			
			if(!method_exists($class, $action) and !method_exists($class, '__call')) {
				self::web_error(404);
			}
			
			print( is_string($result = $class->$action()) ? $result : '500 Internal Server Error' );
		}
		
		/**
		 * Module`s classes loader
		 *
		 * @param string $class
		 * @return void
		 *
		 */
		protected static function modules_autoloader($class)
		{ 
			if(class_exists($class, false)) { return; }
			
			$dir = \Raptor\Config::ROOT . self::SEPARATOR . self::MODS . self::SEPARATOR;
			
			foreach(scandir($dir) as $f)
			{
				if($f == '.' or $f == '..') { continue; }

				if(!file_exists($dir . self::SEPARATOR . $f . self::SEPARATOR . str_replace('\\', '/', $class) . self::EXT)) { continue; }
				
				require_once($dir . self::SEPARATOR . $f . self::SEPARATOR . str_replace('\\', '/', $class) . self::EXT);
			}
		}
		
		/**
		 * Handles shutting down
		 */
		public static function shutdown()
		{
			\Raptor\EventListener::invoke('shutdown');
		}
	}