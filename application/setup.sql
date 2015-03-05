CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `group` varchar(100) NOT NULL,
  `url` varchar(250) NOT NULL,
  `title` varchar(100) NOT NULL,
  `class` varchar(100) default NULL,
  `hide` tinyint(1) unsigned NOT NULL default '0',
  `auth` tinyint(4) unsigned NOT NULL default '0',
  `order` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `pid` int(11) unsigned default NULL,
  `title` varchar(70) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `mid` int(11) unsigned default NULL,
  `hide` tinyint(1) unsigned NOT NULL default '0',
  `l_key` int(11) unsigned NOT NULL,
  `r_key` int(11) unsigned NOT NULL,
  `level` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uniq_alias` (`alias`),
  UNIQUE KEY `uniq_title` (`title`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `page_contents` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `page_id` int(11) unsigned NOT NULL,
  `h1` varchar(512) NOT NULL,
  `teaser` text,
  `content` text,
  `keywords` varchar(255) default NULL,
  `description` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `fk_page_id` (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `site` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `logo` varchar(50) default NULL,
  `slogan` varchar(250) default NULL,
  `brief` text,
  `content` text,
  `keywords` varchar(250) default NULL,
  `description` varchar(250) default NULL,
  `skin` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `site`
--

INSERT INTO `site` (`id`, `name`, `logo`, `slogan`, `brief`, `content`, `keywords`, `description`, `skin`) VALUES
(1, 'Компьютерный центр', 'Компьютерный центр', 'Слоган', '', '<p>Текст на главной</p>', 'key', 'desc', 'rcc');

ALTER TABLE `page_contents`
  ADD CONSTRAINT `fk_page_contents_page_id` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE;
  
  
  CREATE TABLE IF NOT EXISTS `stop_list` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `ip` TEXT,
  `count` int(10),
  `expires` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

  CREATE TABLE IF NOT EXISTS `black_list` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `ip` TEXT,
  `active` int(2),
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

  CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `ip` varchar(255),
  `user_id` int(11) NOT NULL,
  `action` varchar(255),
  `content` TEXT,
  `created` int(12),
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `deferred` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `ball` int(12) default NULL,
  `type_id` int(11) NOT NULL,
  `created` int(12) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;