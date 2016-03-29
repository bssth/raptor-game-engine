<?php
	
	/**
	 * Raptor autoloader inits root and starts core
	 *
	 * @version 1.1
	 * @author Mike Chip
	 */
	
	require_once(__DIR__ . '/Raptor/Core.php'); // loading core 
		
	\Raptor\Core::init(); // init core