<?php
	
/*
	** @comment Простой парсер bb-кодов и смайлов
	** @todo Добавление своих BB-кодов
	** @last_edit 24.08.2015
	** @last_autor Mike
*/

	class BBParser
	{
	
		public static $bbcode;
		public static $smiles;
	
		public static function loadLibrary()
		{
			self::$bbcode = array( 
				'[b]' => '<b>', 
				'[/b]' => '</b>', 
				'[img]' => '<img src="', 
				'[/img]' => '">"',
				'[url]' => '<a href="',
				'[/url]' => '">ссылка</a>',
				'[i]' => '<i>',
				'[/i]' => '</i>',
				'[s]' => '<s>',
				'[/s]' => '</s>',
				'[u]' => '<u>',
				'[/u]' => '</u>',
				'[hr]' => '<hr>'
			);
			self::$smiles = Database::GetOne("config", array("mod" => "smiles"));
		}
	
		public static function parseAll($string)
		{
			$string = self::parseSmiles($string);
			$string = self::parseBB($string);
			return $string;
		}
		
		public static function parseSmiles($string)
		{
			$var = self::$smiles;
			foreach($var as $str => $f)
			{
				if(!is_string($f)) { continue; }
				$string = str_replace($str, "<img src='/storage/smiles/" . $f . "'>", $string);
			}
			return $string;
		}
		
		public static function parseBB($string)
		{
			$var = self::$bbcode;
			foreach($var as $str => $replace)
			{
				if(!is_string($replace)) { continue; }
				$string = str_replace($str, $replace, $string);
			}
			return $string;
		}
	}