<?php defined('SYSPATH') or die('No direct script access.');
 /**
 * Its a test task class
 *
 * @author k1785
 */
class Migrate_Tockeninstall extends Migrate {
      
      
      public static function init(){
        
      }
      
      public static function action_up(){
         DB::query(Database::INSERT,"CREATE TABLE IF NOT EXISTS `house` (
              `id` int(10) NOT NULL AUTO_INCREMENT,
              `level` int(5) NOT NULL,
              `description` varchar(255) NOT NULL,
              `number` int(10) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;")->execute();
         DB::query(Database::INSERT,"INSERT INTO `house` (`id`, `level`, `description`, `number`) VALUES (1, 10, 'Новый дом', 5);")->execute();
         DB::query(Database::INSERT,"CREATE TABLE IF NOT EXISTS `lift` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `level` int(5) NOT NULL,
          `number` int(11) NOT NULL,
          `house_id` int(11) NOT NULL,
          `status` int(11) DEFAULT NULL,
          `current` int(5) NOT NULL,
          `direction` varchar(5) NOT NULL,
          `updated` int(11) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;")->execute();

        DB::query(Database::INSERT,"INSERT INTO `lift` (`id`, `level`, `number`, `house_id`, `status`, `current`, `direction`, `updated`) VALUES
        (2, 1, 1, 1, 0, 1, 'down', 1425203987),
        (3, 1, 2, 1, 0, 1, 'down', 1425203983),
        (4, 1, 3, 1, 0, 1, 'up', 1425203983),
        (5, 1, 4, 1, 0, 1, 'up', 1425158356);")->execute();
        
        DB::query(Database::INSERT,"CREATE TABLE IF NOT EXISTS `log` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `event` varchar(255) NOT NULL,
          `target` varchar(255) NOT NULL,
          `target_id` int(11) NOT NULL,
          `level` int(11) NOT NULL,
          `distance` int(11) NOT NULL,
          `content` text NOT NULL,
          `created` int(11) NOT NULL,
          `user_id` int(11) NOT NULL,
          `ip` text NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;")->execute();
        
        DB::query(Database::INSERT,"CREATE TABLE IF NOT EXISTS `request` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `lift_id` int(11) NOT NULL,
          `house_id` int(11) NOT NULL,
          `user_id` int(11) NOT NULL,
          `level` int(5) NOT NULL,
          `direction` varchar(5) NOT NULL,
          `status` int(5) NOT NULL,
          `created` int(12) NOT NULL,
          `ip` varchar(255) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;")->execute();
        
        DB::query(Database::INSERT,"CREATE TABLE IF NOT EXISTS `roles` (
          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `name` varchar(32) NOT NULL,
          `description` varchar(255) NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `uniq_name` (`name`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;")->execute();
        
        DB::query(Database::INSERT,"INSERT INTO `roles` (`id`, `name`, `description`) VALUES
        (1, 'login', 'Login privileges, granted after account confirmation'),
        (2, 'admin', 'Administrative user, has access to everything.');")->execute();
        
        DB::query(Database::INSERT,"CREATE TABLE IF NOT EXISTS `roles_users` (
          `user_id` int(10) unsigned NOT NULL,
          `role_id` int(10) unsigned NOT NULL,
          PRIMARY KEY (`user_id`,`role_id`),
          KEY `fk_role_id` (`role_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;")->execute();
        
        DB::query(Database::INSERT,"INSERT INTO `roles_users` (`user_id`, `role_id`) VALUES
            (3, 1),
            (5, 1),
            (3, 2);")->execute();
            
        DB::query(Database::INSERT,"CREATE TABLE IF NOT EXISTS `site` (
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
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;")->execute();
        
        DB::query(Database::INSERT,"CREATE TABLE IF NOT EXISTS `users` (
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
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;")->execute();   
        
        DB::query(Database::INSERT,"INSERT INTO `users` (`id`, `login`, `email`, `phone`, `name`, `address`, `password`, `created`, `logins`, `last_login`, `active_time`) VALUES
(3, 'admin', 'scorp1785@mail.ru', '+79374353527', 'Кульков Игорь', 'г. Пенза, ул. Гагарина, 16', '671728946c25115dfb7ac934d2dae387b5f45da2fad065fadb960ecbec597948', 1378369661, 69, 1425146904, NULL),
(4, 'guest', 'guest@guest.guest', 'guest', 'guest', 'guest', '', NULL, 0, NULL, NULL),
(5, 'igor', 'm@mail.ru', '', 'Кульков Игорь Владимирович', '', '6b35a61fc2051fb23485494b4e8f5283c4a1643bcab1a13f67c15d4d48ac8037', 1425132214, 0, NULL, NULL);")->execute();

                DB::query(Database::INSERT,"CREATE TABLE IF NOT EXISTS `user_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `user_agent` varchar(40) NOT NULL,
  `token` varchar(40) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_token` (`token`),
  KEY `fk_user_id` (`user_id`),
  KEY `expires` (`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;")->execute();
          echo 'up';  
         return TRUE;
         //up 
      }
      
      public static function action_down(){
            DB::query(Database::DELETE, 'DROP TABLE lift')->execute();
            DB::query(Database::DELETE, 'DROP TABLE house')->execute();
            DB::query(Database::DELETE, 'DROP TABLE request')->execute();
            DB::query(Database::DELETE, 'DROP TABLE users')->execute();
            DB::query(Database::DELETE, 'DROP TABLE roles')->execute();
            DB::query(Database::DELETE, 'DROP TABLE site')->execute();
            DB::query(Database::DELETE, 'DROP TABLE user_tokens')->execute();
            DB::query(Database::DELETE, 'DROP TABLE roles_users')->execute();
            DB::query(Database::DELETE, 'DROP TABLE log')->execute();
            echo 'drop';
            return TRUE;
      }
}
