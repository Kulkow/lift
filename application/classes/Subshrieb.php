<?php defined('SYSPATH') or die('No direct script access.');

class Subshrieb
{
    
    public static function send($user, $action, $params = array(), $data = NULL)
    {
       if($user instanceof ORM)
       {
          if(! $user->loaded())
          {
            return false;
          }
          if(! $data)
          {
             $fields = $user->as_array(); 
          }
          else
          {
             $fields = $data;   
          }
          
   
          $phone = Arr::get($fields, 'phone', NULL);
          $email = Arr::get($fields, 'email', NULL);
          $subshrieb = ORM::factory('subshrieb', array('user_id' => $user->id));
          if(! $subshrieb->loaded())
          {
            //echo 'add';
            /**
            *       