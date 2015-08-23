<?php 

/*
	@last_edit 22.08.2015
	@last_autor Mike
	@comment Драйвер создан на случай, если файл в хранилище не найден и вызывается роутер
*/

class storageDriver() {
	function __call() {
		echo "<h1>Элемент хранилища не найден</h1>";
	}
}