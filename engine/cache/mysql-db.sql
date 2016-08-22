SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8;

CREATE TABLE IF NOT EXISTS `amenu` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `href` varchar(80) NOT NULL DEFAULT '',
  `title` varchar(80) NOT NULL DEFAULT '',
  `class` varchar(80) NOT NULL DEFAULT '',
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

INSERT INTO `amenu` (`_id`, `href`, `title`, `class`) VALUES
(1, '/admin/index', 'Главная', 'fa fa-dashboard fa-fw'),
(2, '/admin/chars', 'Персонажи', 'fa fa-group fa-fw'),
(3, '/admin/templates', 'Шаблоны', 'fa fa-pencil fa-fw'),
(4, '/admin/scripts', 'Скрипты', 'fa fa-file-code-o fa-fw'),
(5, '/admin/mailing', 'Рассылки', 'fa fa-mail-forward fa-fw'),
(6, '/admin/locations', 'Локации', 'fa fa-globe fa-fw'),
(7, '/admin/settings', 'Настройки', 'fa fa-magic fa-fw'),
(8, '/admin/modules', 'Модули', 'fa fa-external-link fa-fw'),
(9, '/admin/news', 'Новости', 'fa fa-file fa-fw'),
(10, '/admin/engine', 'Движок', 'fa fa-heart fa-fw');

CREATE TABLE IF NOT EXISTS `characters` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `player` varchar(80) NOT NULL DEFAULT '',
  `info` text NOT NULL,
  `inventory` text NOT NULL,
  `money` text NOT NULL,
  `online` int(11) NOT NULL DEFAULT '0',
  `ban` int(11) NOT NULL DEFAULT '0',
  `location` int(11) NOT NULL DEFAULT '0',
  `pos_x` int(11) NOT NULL DEFAULT '0',
  `pos_y` int(11) NOT NULL DEFAULT '0',
  `pos_z` int(11) NOT NULL DEFAULT '0',
  `vworld` int(11) NOT NULL DEFAULT '0',
  `perms` text NOT NULL,
  `status` varchar(80) NOT NULL DEFAULT '',
  PRIMARY KEY (`_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `characters` (`_id`, `name`, `player`, `info`, `inventory`, `money`, `online`, `ban`, `location`, `pos_x`, `pos_y`, `pos_z`, `vworld`, `perms`, `status`) VALUES
(1, 'admin', '1', 'a:0:{}', 'a:0:{}', 'a:0:{}', 1471609179, 0, 1, 0, 0, 0, 0, 'a:1:{i:0;s:9:"admin.all";}', 'Administrator');

CREATE TABLE IF NOT EXISTS `char_act` (
  `_id` varchar(80) NOT NULL,
  `name` varchar(80) NOT NULL DEFAULT ' ',
  `eval` text NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `char_act` (`_id`, `name`, `eval`) VALUES
('test', 'проверка [безфункциональная]', 'return true;');

CREATE TABLE IF NOT EXISTS `char_params` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `param` varchar(80) NOT NULL,
  `name` varchar(80) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'integer',
  `default` varchar(35) NOT NULL DEFAULT '0',
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS `locations` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT 'без имени',
  `desc` text NOT NULL,
  `vars` varchar(250) NOT NULL DEFAULT '[]',
  `type` varchar(80) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `logs` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `char` varchar(80) NOT NULL DEFAULT '',
  `icon` varchar(80) NOT NULL DEFAULT '',
  `desc` varchar(80) NOT NULL DEFAULT '',
  `cat` varchar(80) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

CREATE TABLE IF NOT EXISTS `players` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(80) NOT NULL,
  `password` varchar(80) NOT NULL DEFAULT ' ',
  `last_ip` varchar(16) NOT NULL DEFAULT '0.0.0.0',
  `last_date` varchar(50) NOT NULL DEFAULT '0',
  `reg_ip` varchar(16) NOT NULL DEFAULT '0.0.0.0',
  `reg_date` varchar(50) NOT NULL DEFAULT '0',
  `domain` varchar(50) NOT NULL DEFAULT 'undefined',
  PRIMARY KEY (`_id`),
  UNIQUE KEY `login` (`login`) 
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `players` (`_id`, `login`, `password`, `last_ip`, `last_date`, `reg_ip`, `reg_date`, `domain`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997 ', '127.0.0.1', '1471603388', '127.0.0.1', '1467751785', '-');

CREATE TABLE IF NOT EXISTS `rnews` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(80) NOT NULL,
  `short` text NOT NULL,
  `full` text NOT NULL,
  `time` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `show` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `char_bought` (
  `_id` int(11) NOT NULL,
  `char` int(11) NOT NULL DEFAULT '0',
  `item` varchar(80) NOT NULL DEFAULT '0',
  `expires` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `char_bought` CHANGE `_id` `_id` INT(11) NOT NULL AUTO_INCREMENT; 

CREATE TABLE IF NOT EXISTS `currency` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `desc` text NOT NULL,
  `image` varchar(200) NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;