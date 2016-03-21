<?php
	/**
	 * Class provides Raptor config
	 */
	
	namespace Game;
	
	class Config extends \Fructum\Config
	{
		
		const mongo_host = null; // mongodb host; if null, engine will use localhost ignoring login and password
		const mongo_user = null; // mongodb user 
		const mongo_password = null; // mongodb password
		const mongo_db = 'admin'; // mongodb database name
		
		const game_title = 'RAPTOR Game'; // game title (for main page)
		const meta_desc = 'This is sample RAPTOR Game Engine game'; // meta description
		const meta_keys = 'raptor,game,engine,project,game,mmorpg,test,sample,example'; // meta keywords
		const sample_storage = 'http://s1.blockstudio.net/raptor'; // root of sample static files storage  
		const copyright = 'RAPTOR Game Engine by Disaytid';
		
	}