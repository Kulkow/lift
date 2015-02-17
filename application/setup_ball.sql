CREATE TABLE IF NOT EXISTS `cards` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(155) NOT NULL,
  `ball` int(12) NOT NULL,
  `last_event` int(11) NOT NULL,
  `created` int(12) NOT NULL,
  `active` int(12) NOT NULL,
  `active_time` int(12) NOT NULL,
  `user_id` int(12) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uniq_code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `cards_users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `card_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY  (`user_id`,`card_id`),
  KEY `fk_card_id` (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `cards_users`
  ADD CONSTRAINT `cards_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cards_users_ibfk_2` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE;
  
  
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type_id` varchar(155) NOT NULL,
  `card_id` int(12) NOT NULL,
  `created` int(12) NOT NULL,
  `robot` int(12) NOT NULL,
  `user_id` int(12) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `events_users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `event_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY  (`user_id`,`event_id`),
  KEY `fk_event_id` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `events_types` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(64) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uniq_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `events_users`
  ADD CONSTRAINT `events_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `events_users_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;