-- phpMyAdmin SQL Dump
-- version 4.0.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 15 2015 г., 22:51
-- Версия сервера: 5.5.38-log
-- Версия PHP: 5.5.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `lift`
--

-- --------------------------------------------------------

--
-- Структура таблицы `house`
--

CREATE TABLE IF NOT EXISTS `house` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `level` int(5) NOT NULL,
  `description` varchar(255) NOT NULL,
  `number` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `house`
--

INSERT INTO `house` (`id`, `level`, `description`, `number`) VALUES
(1, 10, 'Новый дом', 5);

-- --------------------------------------------------------

--
-- Структура таблицы `lift`
--

CREATE TABLE IF NOT EXISTS `lift` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(5) NOT NULL,
  `number` int(11) NOT NULL,
  `house_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `current` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `lift`
--

INSERT INTO `lift` (`id`, `level`, `number`, `house_id`, `status`, `current`) VALUES
(2, 8, 1, 1, 'up', 8),
(3, 8, 2, 1, 'up', 8),
(4, 4, 3, 1, 'up', 4),
(5, 6, 4, 1, 'up', 6);

-- --------------------------------------------------------

--
-- Структура таблицы `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event` varchar(255) NOT NULL,
  `target` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `request`
--

CREATE TABLE IF NOT EXISTS `request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lift_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `level` int(5) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created` int(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'login', 'Login privileges, granted after account confirmation'),
(2, 'admin', 'Administrative user, has access to everything.');

-- --------------------------------------------------------

--
-- Структура таблицы `roles_users`
--

CREATE TABLE IF NOT EXISTS `roles_users` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `roles_users`
--

INSERT INTO `roles_users` (`user_id`, `role_id`) VALUES
(3, 1),
(3, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `site`
--

CREATE TABLE IF NOT EXISTS `site` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `logo` varchar(50) DEFAULT NULL,
  `slogan` varchar(250) DEFAULT NULL,
  `brief` text,
  `content` text,
  `keywords` varchar(250) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `skin` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `site`
--

INSERT INTO `site` (`id`, `name`, `logo`, `slogan`, `brief`, `content`, `keywords`, `description`, `skin`) VALUES
(1, 'Работа 4 лифтов в доме', 'Работа 4 лифтов в доме', 'Слоган', '', '<p>Текст на главной</p>', 'key', 'desc', 'rcc');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(64) NOT NULL,
  `email` varchar(254) NOT NULL,
  `phone` varchar(254) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `address` text,
  `password` varchar(64) NOT NULL,
  `created` int(11) DEFAULT NULL,
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` int(10) unsigned DEFAULT NULL,
  `active_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `email`, `phone`, `name`, `address`, `password`, `created`, `logins`, `last_login`, `active_time`) VALUES
(3, 'admin', 'scorp1785@mail.ru', '+79374353527', 'Кульков Игорь', 'г. Пенза, ул. Гагарина, 16', '671728946c25115dfb7ac934d2dae387b5f45da2fad065fadb960ecbec597948', 1378369661, 65, 1408031322, NULL),
(4, 'guest', 'guest@guest.guest', 'guest', 'guest', 'guest', '', NULL, 0, NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
