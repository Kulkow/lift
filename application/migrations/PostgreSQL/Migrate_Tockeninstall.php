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
         DB::query(Database::INSERT,"CREATE TABLE IF NOT EXISTS  house  (
               id  SERIAL,
               level  integer NOT NULL,
               description  varchar NOT NULL,
               number  integer NOT NULL
            );")->execute();
         DB::query(Database::INSERT,"INSERT INTO  house  ( id ,  level ,  description ,  number ) VALUES (1, 10, 'Новый дом', 5);")->execute();
         DB::query(Database::INSERT,"CREATE TABLE IF NOT EXISTS  lift  (
           id  SERIAL,
           level  integer NOT NULL,
           number  integer NOT NULL,
           house_id  integer NOT NULL,
           status  integer DEFAULT NULL,
           current  integer NOT NULL,
           direction  varchar NOT NULL,
           updated  integer NOT NULL
        );")->execute();

        DB::query(Database::INSERT,"INSERT INTO  lift  ( id ,  level ,  number ,  house_id ,  status ,  current ,  direction ,  updated ) VALUES
        (2, 1, 1, 1, 0, 1, 'down', 1425203987),
        (3, 1, 2, 1, 0, 1, 'down', 1425203983),
        (4, 1, 3, 1, 0, 1, 'up', 1425203983),
        (5, 1, 4, 1, 0, 1, 'up', 1425158356);")->execute();
        
        DB::query(Database::INSERT,"CREATE TABLE IF NOT EXISTS  log  (
           id  SERIAL,
           event  varchar NOT NULL,
           target  varchar NOT NULL,
           target_id  integer NOT NULL,
           level  integer NOT NULL,
           distance  integer DEFAULT NULL,
           content  text NOT NULL,
           created  integer NOT NULL,
           user_id  integer NOT NULL,
           ip  text NOT NULL          
        );")->execute();
        
        DB::query(Database::INSERT,"CREATE TABLE IF NOT EXISTS  request  (
           id  SERIAL,
           lift_id  integer NOT NULL,
           house_id  integer NOT NULL,
           user_id  integer NOT NULL,
           level  integer NOT NULL,
           direction  varchar NOT NULL,
           status  integer DEFAULT NULL,
           created  integer NOT NULL,
           ip  varchar NOT NULL
        );")->execute();
        
        DB::query(Database::INSERT,"CREATE TABLE IF NOT EXISTS  roles  (
           id  SERIAL,
           name  varchar UNIQUE NOT NULL,
           description  varchar NOT NULL)")->execute();
        
        DB::query(Database::INSERT,"INSERT INTO  roles  ( id ,  name ,  description ) VALUES
        (1, 'login', 'Login privileges, granted after account confirmation'),
        (2, 'admin', 'Administrative user, has access to everything.');")->execute();
        
        DB::query(Database::INSERT,"CREATE TABLE IF NOT EXISTS  roles_users  (
           user_id  integer NOT NULL,
           role_id  integer NOT NULL);")->execute();
        
        DB::query(Database::INSERT,"INSERT INTO  roles_users  ( user_id ,  role_id ) VALUES
            (3, 1),
            (5, 1),
            (3, 2);")->execute();
            
        DB::query(Database::INSERT,"CREATE TABLE IF NOT EXISTS  site  (
           id  SERIAL,
           name  varchar NOT NULL,
           logo  varchar DEFAULT NULL,
           slogan  varchar DEFAULT NULL,
           brief  text,
           content  text,
           keywords  varchar DEFAULT NULL,
           description  varchar DEFAULT NULL,
           skin  varchar NOT NULL
        );")->execute();
        
        DB::query(Database::INSERT,"CREATE TABLE IF NOT EXISTS  users  (
               id  SERIAL,
               login  varchar NOT NULL,
               email  varchar NOT NULL,
               phone  varchar DEFAULT NULL,
               name  varchar NOT NULL,
               address  text,
               password  varchar NOT NULL,
               created  integer DEFAULT NULL,
               logins  integer  NOT NULL DEFAULT '0',
               last_login  integer  DEFAULT NULL,
               active_time  integer DEFAULT NULL);")->execute();   
        
        DB::query(Database::INSERT,"INSERT INTO  users  ( id ,  login ,  email ,  phone ,  name ,  address ,  password ,  created ,  logins ,  last_login ,  active_time ) VALUES
(3, 'admin', 'scorp1785@mail.ru', '+79374353527', 'Кульков Игорь', 'г. Пенза, ул. Гагарина, 16', '671728946c25115dfb7ac934d2dae387b5f45da2fad065fadb960ecbec597948', 1378369661, 69, 1425146904, NULL),
(4, 'guest', 'guest@guest.guest', 'guest', 'guest', 'guest', '', NULL, 0, NULL, NULL),
(5, 'igor', 'm@mail.ru', '', 'Кульков Игорь Владимирович', '', '6b35a61fc2051fb23485494b4e8f5283c4a1643bcab1a13f67c15d4d48ac8037', 1425132214, 0, NULL, NULL);")->execute();

      DB::query(Database::INSERT,"CREATE TABLE IF NOT EXISTS  user_tokens  (
            id  serial,
            user_id  integer NOT NULL,
            user_agent  varchar NOT NULL,
            token  varchar UNIQUE NOT NULL,
            created  integer NOT NULL,
            expires  integer NOT NULL);")->execute(); 
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
