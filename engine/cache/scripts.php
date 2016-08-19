<?php // Тэг не удалять! Don't delete PHP tag!

	/**
	  * Данный скрипт является шаблоном для ваших обработчиков. Здесь можно перехватить любое событие, которое происходит в игре, 
	  * и как-то на него отреагировать. Примеры создания обработчика:
	  *
	  * new \Raptor\EventListener('player_login', function($e, $player, $char) { echo "{$player} вошел как персонаж {$char}"; })
	  *
	  * Поначалу это может казаться сложным, но позже вы привыкнете, и обработчики станут вашими лучшими друзьями
	  * player_login в примере выше - собственно обработчик, $player и $char - параметры. Первый параметр $e - опять обработчик, переменную можно
	  * проигнорировать. Список обработчиков и их параметров находится ниже, вы можете использовать любой из них
	  */
  
	new \Raptor\EventListener('ready', function($e) 
	{ 
		// обработчик вызывается, когда ядро загружено
		// не запускайте здесь трудоёмкие процессы - обработчик вызывается при загрузке ядра КАЖДЫМ запросом
	}); 
	
	new \Raptor\EventListener('char_event', function($e, $id, $act) 
	{ 
		// вызывается, когда персонаж отправил произвольное действие
		// $id - ID персонажа. 
		// $act - действие
	});
	
	new \Raptor\EventListener('registered', function($e, $login) 
	{ 
		// вызывается при регистрации игрока. 
		// $login - логин игрока. По нему можно найти его в БД
	});
	
	new \Raptor\EventListener('set_perms', function($e, $perm, $status) 
	{ 
		// вызывается при изменении прав доступа. 
		// $perm - наименование, 
		// $status - новый статус (true/false)
	});
	
	new \Raptor\EventListener('char_teleported', function($e, $char, $location) 
	{ 
		// вызывается при изменении локации персонажа 
		// $char - ID персонажа 
		// $location - ID локации
	});
	
	new \Raptor\EventListener('changed_char', function($e, $key, $value) 
	{ 
		// вызывается при изменении любого поля персонажа. 
		// $key, $value - ключ и значение
	});
	
	new \Raptor\EventListener('char_act', function($e, $char, $target, $act) 
	{ 
		// вызывается, когда персонаж нажимает на кнопку в контекстном меню другого персонажа. 
		// $char - ID нажавшего, 
		// $target - ID персонажа, на которого нажали. 
		// $act - ID действия
	});
	
	new \Raptor\EventListener('on_online', function($e, $id) 
	{ 
		// вызывается, когда персонаж изменяет статус на онлайн. $id - ID персонажа
	});
	
	new \Raptor\EventListener('mainpage', function($e) 
	{ 
		// вызывается при запросе главной страницы
	});
	
	new \Raptor\EventListener('gui_load', function($e, $char) 
	{ 
		// вызывается при загрузке /p страницы - интерфейса игры. $char - ID персонажа
	});

	new \Raptor\EventListener('login', function($e, $id) 
	{ 
		// вызывается при входе игрока. $id - его ID
	});
	
	new \Raptor\EventListener('char_log', function($e, $id) 
	{ 
		// вызывается при выборе персонажа в кабинете. $id - ID персонажа
	});
	