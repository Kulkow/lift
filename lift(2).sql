-- phpMyAdmin SQL Dump
-- version 4.0.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 23 2015 г., 23:20
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
  `status` int(11) DEFAULT NULL,
  `current` int(5) NOT NULL,
  `direction` varchar(5) NOT NULL,
  `updated` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `lift`
--

INSERT INTO `lift` (`id`, `level`, `number`, `house_id`, `status`, `current`, `direction`, `updated`) VALUES
(2, 3, 1, 1, 2, 3, 'up', 0),
(3, 7, 2, 1, 2, 7, 'up', 0),
(4, 10, 3, 1, 2, 10, 'up', 0),
(5, 5, 4, 1, 2, 5, 'up', 0);

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
  `direction` varchar(5) NOT NULL,
  `status` int(5) NOT NULL,
  `created` int(12) NOT NULL,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=120 ;

--
-- Дамп данных таблицы `request`
--

INSERT INTO `request` (`id`, `lift_id`, `user_id`, `level`, `direction`, `status`, `created`, `ip`) VALUES
(2, 2, 3, 5, 'up', 0, 0, ''),
(3, 2, 3, 5, 'down', 0, 0, ''),
(4, 2, 3, 5, 'down', 0, 0, ''),
(5, 2, 3, 5, 'down', 0, 0, ''),
(7, 2, 4, 5, 'down', 0, 1424545001, '127.0.0.1'),
(8, 2, 4, 5, 'down', 0, 1424545400, '127.0.0.1'),
(9, 2, 4, 5, 'down', 0, 1424545456, '127.0.0.1'),
(10, 2, 4, 2, 'up', 0, 1424546087, '127.0.0.1'),
(11, 2, 4, 2, 'up', 0, 1424546106, '127.0.0.1'),
(12, 2, 4, 3, 'up', 0, 1424546262, '127.0.0.1'),
(13, 2, 4, 3, 'down', 0, 1424546280, '127.0.0.1'),
(14, 2, 4, 4, 'down', 0, 1424546422, '127.0.0.1'),
(15, 2, 4, 2, 'down', 0, 1424546502, '127.0.0.1'),
(16, 2, 4, 2, 'down', 0, 1424546669, '127.0.0.1'),
(17, 2, 4, 2, 'down', 0, 1424546686, '127.0.0.1'),
(18, 2, 4, 2, 'down', 0, 1424546810, '127.0.0.1'),
(19, 2, 4, 3, 'down', 0, 1424546837, '127.0.0.1'),
(20, 2, 4, 3, 'down', 0, 1424546881, '127.0.0.1'),
(21, 2, 4, 3, 'down', 0, 1424546964, '127.0.0.1'),
(22, 2, 4, 2, 'up', 0, 1424547068, '127.0.0.1'),
(23, 2, 4, 2, 'up', 0, 1424547109, '127.0.0.1'),
(24, 2, 4, 3, 'down', 0, 1424547155, '127.0.0.1'),
(25, 2, 4, 5, 'down', 0, 1424547389, '127.0.0.1'),
(26, 2, 4, 2, 'down', 0, 1424547422, '127.0.0.1'),
(27, 2, 4, 1, 'down', 0, 1424547560, '127.0.0.1'),
(28, 2, 4, 2, 'down', 0, 1424547600, '127.0.0.1'),
(29, 2, 4, 5, 'down', 0, 1424547709, '127.0.0.1'),
(30, 2, 4, 5, 'down', 0, 1424547771, '127.0.0.1'),
(31, 2, 4, 5, 'down', 0, 1424547822, '127.0.0.1'),
(32, 2, 4, 5, 'down', 0, 1424547857, '127.0.0.1'),
(33, 2, 4, 5, 'down', 0, 1424547985, '127.0.0.1'),
(34, 2, 4, 4, 'up', 0, 1424548020, '127.0.0.1'),
(35, 2, 4, 4, 'up', 0, 1424548093, '127.0.0.1'),
(36, 2, 4, 4, 'up', 0, 1424548125, '127.0.0.1'),
(37, 2, 4, 4, 'down', 0, 1424548158, '127.0.0.1'),
(38, 2, 4, 4, 'up', 0, 1424548234, '127.0.0.1'),
(39, 2, 4, 5, 'down', 0, 1424548364, '127.0.0.1'),
(40, 2, 4, 1, 'down', 0, 1424548412, '127.0.0.1'),
(41, 2, 4, 1, 'down', 0, 1424548454, '127.0.0.1'),
(42, 2, 4, 7, 'down', 0, 1424548536, '127.0.0.1'),
(43, 2, 4, 7, 'down', 0, 1424548565, '127.0.0.1'),
(44, 2, 4, 7, 'down', 0, 1424548613, '127.0.0.1'),
(45, 2, 4, 7, 'down', 0, 1424548642, '127.0.0.1'),
(46, 2, 4, 5, 'down', 0, 1424548689, '127.0.0.1'),
(47, 2, 4, 5, 'down', 0, 1424548715, '127.0.0.1'),
(48, 2, 4, 1, 'down', 0, 1424548762, '127.0.0.1'),
(49, 2, 4, 7, 'down', 0, 1424548878, '127.0.0.1'),
(50, 2, 4, 6, 'down', 0, 1424548929, '127.0.0.1'),
(51, 2, 4, 6, 'down', 0, 1424548977, '127.0.0.1'),
(52, 2, 4, 5, 'up', 0, 1424548984, '127.0.0.1'),
(53, 2, 4, 4, 'down', 0, 1424548996, '127.0.0.1'),
(54, 2, 4, 5, 'down', 0, 1424549019, '127.0.0.1'),
(55, 2, 4, 6, 'down', 0, 1424549086, '127.0.0.1'),
(56, 2, 4, 6, 'down', 0, 1424549130, '127.0.0.1'),
(57, 2, 4, 4, 'down', 0, 1424549339, '127.0.0.1'),
(58, 2, 4, 5, 'down', 0, 1424549361, '127.0.0.1'),
(59, 2, 4, 5, 'down', 0, 1424549476, '127.0.0.1'),
(60, 2, 4, 5, 'down', 0, 1424549514, '127.0.0.1'),
(61, 2, 4, 6, 'down', 0, 1424549557, '127.0.0.1'),
(62, 2, 4, 6, 'up', 0, 1424549621, '127.0.0.1'),
(63, 2, 4, 5, 'down', 0, 1424549691, '127.0.0.1'),
(64, 2, 4, 6, 'down', 0, 1424549715, '127.0.0.1'),
(65, 2, 4, 5, 'down', 0, 1424549738, '127.0.0.1'),
(66, 2, 4, 1, 'down', 0, 1424549772, '127.0.0.1'),
(67, 2, 4, 5, 'down', 0, 1424549944, '127.0.0.1'),
(68, 2, 4, 6, 'down', 0, 1424550019, '127.0.0.1'),
(69, 2, 4, 6, 'down', 0, 1424550620, '127.0.0.1'),
(70, 2, 4, 6, 'down', 0, 1424550727, '127.0.0.1'),
(71, 2, 4, 6, 'down', 0, 1424550808, '127.0.0.1'),
(72, 2, 4, 6, 'up', 0, 1424550812, '127.0.0.1'),
(73, 2, 4, 6, 'up', 0, 1424550964, '127.0.0.1'),
(74, 2, 4, 5, 'down', 0, 1424551029, '127.0.0.1'),
(75, 2, 4, 6, 'down', 0, 1424551064, '127.0.0.1'),
(76, 2, 4, 7, 'down', 0, 1424554496, '127.0.0.1'),
(77, 2, 4, 7, 'down', 0, 1424554559, '127.0.0.1'),
(78, 2, 4, 7, 'up', 0, 1424554570, '127.0.0.1'),
(79, 2, 4, 6, 'down', 0, 1424554580, '127.0.0.1'),
(80, 2, 4, 6, 'up', 0, 1424554612, '127.0.0.1'),
(81, 2, 4, 7, 'up', 0, 1424554660, '127.0.0.1'),
(82, 2, 4, 1, 'down', 0, 1424554736, '127.0.0.1'),
(83, 2, 4, 1, 'up', 0, 1424554749, '127.0.0.1'),
(84, 2, 4, 2, 'up', 0, 1424554863, '127.0.0.1'),
(85, 2, 4, 2, 'down', 0, 1424554872, '127.0.0.1'),
(86, 2, 4, 3, 'up', 0, 1424554885, '127.0.0.1'),
(87, 2, 4, 1, 'up', 0, 1424554900, '127.0.0.1'),
(88, 2, 4, 2, 'up', 0, 1424554962, '127.0.0.1'),
(89, 2, 4, 2, 'down', 0, 1424716439, '127.0.0.1'),
(90, 2, 4, 3, 'up', 0, 1424716449, '127.0.0.1'),
(91, 2, 4, 2, 'down', 0, 1424716500, '127.0.0.1'),
(92, 2, 4, 1, 'up', 0, 1424716541, '127.0.0.1'),
(93, 2, 4, 2, 'down', 0, 1424716564, '127.0.0.1'),
(94, 2, 4, 1, 'down', 0, 1424716584, '127.0.0.1'),
(95, 2, 4, 2, 'down', 0, 1424716634, '127.0.0.1'),
(96, 2, 4, 2, 'up', 0, 1424716636, '127.0.0.1'),
(97, 2, 4, 3, 'up', 0, 1424716641, '127.0.0.1'),
(98, 2, 4, 2, 'down', 0, 1424716684, '127.0.0.1'),
(99, 2, 4, 3, 'down', 0, 1424716707, '127.0.0.1'),
(100, 2, 4, 2, 'down', 0, 1424716782, '127.0.0.1'),
(101, 2, 4, 3, 'down', 0, 1424716799, '127.0.0.1'),
(102, 2, 4, 2, 'down', 0, 1424716848, '127.0.0.1'),
(103, 2, 4, 2, 'down', 0, 1424716917, '127.0.0.1'),
(104, 2, 4, 3, 'up', 0, 1424716920, '127.0.0.1'),
(105, 2, 4, 2, 'down', 0, 1424717244, '127.0.0.1'),
(106, 2, 4, 2, 'down', 0, 1424717498, '127.0.0.1'),
(107, 2, 4, 3, 'down', 0, 1424717519, '127.0.0.1'),
(108, 2, 4, 4, 'down', 0, 1424717557, '127.0.0.1'),
(109, 2, 4, 2, 'down', 0, 1424717590, '127.0.0.1'),
(110, 2, 4, 3, 'down', 0, 1424717637, '127.0.0.1'),
(111, 2, 4, 2, 'down', 0, 1424717699, '127.0.0.1'),
(112, 2, 4, 3, 'down', 0, 1424717714, '127.0.0.1'),
(113, 5, 4, 7, 'down', 0, 1424718410, '127.0.0.1'),
(114, 3, 4, 8, 'down', 0, 1424718454, '127.0.0.1'),
(115, 3, 4, 7, 'down', 0, 1424718489, '127.0.0.1'),
(116, 3, 4, 6, 'down', 0, 1424718795, '127.0.0.1'),
(117, 4, 4, 6, 'down', 0, 1424718840, '127.0.0.1'),
(118, 4, 4, 2, 'down', 0, 1424719010, '127.0.0.1'),
(119, 5, 4, 3, 'down', 0, 1424719069, '127.0.0.1');

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
(3, 'admin', 'scorp1785@mail.ru', '+79374353527', 'Кульков Игорь', 'г. Пенза, ул. Гагарина, 16', '671728946c25115dfb7ac934d2dae387b5f45da2fad065fadb960ecbec597948', 1378369661, 66, 1424198481, NULL),
(4, 'guest', 'guest@guest.guest', 'guest', 'guest', 'guest', '', NULL, 0, NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
