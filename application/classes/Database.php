<?php defined('SYSPATH') OR die('No direct script access.');

abstract class Database extends Kohana_Database {
    
   // public static $default = 'postgresql';
   
   
   public static function type_driver(){
      $instances = self::$instances;
      $name = null;
      foreach($instances as $_name => $instance){
        $name = $_name;
      }
      if($name){
         $_config = Kohana::$config->load('database')->$name;
         if(! empty($_config['type'])){
            return $_config['type'];
         }  
      }
      return $type;
   }
}