<?php defined('SYSPATH') or die('No direct script access.');

class Controller_1C extends Controller_Layout
{

	/**
    card_id                                         ,
    ball           -    ,                                     ,
    email     email                          (                          ,                                  ),
    phone -                                         (                          ,                                  ),
    activate              (    .    .             :    ),                                                           (                                             )??
    created              (    .    .             :    )                                      .
    type_id                             (1                         , 2                     , 3                       ,    ..)
    code                                                     ,
    hash                           (                         ).
    
    Operation                                                             .                                                     :     
                                                                  (                                     ):
    1 - add                                  (                                                                             ), 
    2     add_no_kesh-                                  ,                                            ;
                             : 
    3 - write -                                                                                               .           ,
    4 - add_derect - "                                 "(                                                      ), 
    5 - write_derect -"                             "(                                                      ), 
    6     clear_start -                                                            , 
    7     clear -                                                                 .

    */
    
    protected $_config_1c = array();
    	

	public function before()
	{

        //echo 'POST';
        //print_r($_POST);
        //parent::before();
        Site::ini();
        $this->auth_user = Auth::instance()->get_user();
        
        /*if(! Firefall::check_ip(Request::$client_ip))
        {
            throw new HTTP_Exception_403();
        }
        */

		if ( ! $this->request->is_ajax())
		{
			
            if ( ! $this->auth_user AND $this->request->controller() != 'auth')
			{
				Session::instance()->set('refer_before_auth', Arr::get($_SERVER, 'REQUEST_URI', '/'));
			}

            $this->site = ORM::factory('site')->find();
			$this->template = View::factory($this->template);
	   	   	$this->template->bind_global('site', $this->site);
            $this->template->bind_global('menu', $this->menu);
            $this->template->bind_global('side_menu', $this->side_menu);
	   	   	$this->template->bind_global('auth_user', $this->auth_user);
	   	   	$this->template->bind_global('sidebar', $this->sidebar);
            
            $this->template->content = NULL;
            
            $config = Kohana::$config->load('1c');
            $this->_config_1c['deferred'] = $config->get('deferred');
            $this->_config_1c['subshrieb'] = $config->get('subshrieb');
            
		}
	}
    
    //public function before()
	//{
        //parent::before();
        
        /*if(! $this->auth_user)
        {
            if ( ! $auth->logged_in('admin'))
            {
               throw new HTTP_Exception_403();
            }  
        }*/
        
        
//	}

	
    
    public function action_index()
	{   /*echo $this->_iconv('1111234567152527282892top_key_tere');
        $a = "1111111234567152527282892top_key_tere";
        $a = '1111111234567152527282892top_key_tere';
        $a = iconv("windows-1251","UTF-8",$a);
        echo md5($a);*/
        
        $debag = FALSE;
        
        if (HTTP_Request::POST == $this->request->method() OR $debag)
		{
            
              $values = $this->request->post();
              $errors = array();  
              if($cards = Arr::get($values, 'card', NULL))
              {
                    //echo 'MULTI';
                    foreach($cards as $index => $card)
                    {
                       $operation = $this->_add_operation($card);
                       if($operation === TRUE)
                       {
                          // OK
                       }
                       else
                       {
                          $errors[$index] = $this->_iconv(implode(',',$operation));
                       }
                    }
                    if(count($errors) > 0)
                     {
                         $str_error = array();
                         foreach($errors as $index => $error)
                         {
                           $str_error[] = 'error_step_'.$index.':'.$error; 
                         }
                         header("HTTP/1.0 404 Not Found");
                         return 'error:'.implode(':', $str_error);  
                     }
                 
                    return 'OK';
              }
              else
              {
                 //echo 'single';
                 $operation = $this->_add_operation($values);
                 if($operation === TRUE)
                 {
                     //echo 'OKI';
                     // OK
                 }
                 else
                 {
                    // echo 'errors';
                    // echo implode(',',$operation);
                     $errors[] = $this->_iconv(implode(',',$operation));
                 }
                 
                 if(count($errors) > 0)
                 {
                     header("HTTP/1.0 404 Not Found");
                     echo 'error:'.implode(',', $errors);
                 }
                 else
                 {
                    echo 'OK';
                 }
              }
              
            $this->template = View::factory('1c');
		}
        else
        {
             $this->next_email();
             $this->template = View::factory('1c');
             //$this->template->content = View::factory('admin/1c')->bind('errors', $errors);            
        }
        
	}
    
    protected function _iconv($str)
    {
        return $str = iconv("UTF-8", "WINDOWS-1251", $str);
        //return $str;
    }
    
    /**
    *                                                          
    */
    protected function _add_operation($values = array())
	{
	  //$debag = TRUE;
      $debag = FALSE;
      
      if($debag)
      {
          $values = array('card_id' => 1111234567,
                          'ball' => 100,
                          'activate' => '25.10.2013 00:00',
                          'created' => '05.10.2013 00:00',
                           'type_id' => 3,
                           'code' => 152527282892,
                           'hash' => '2015AC9533EE91B7B38F4FE1933276B2'); 
      }
      
      try
      {
        $errors = array();
        //print_r($values);
               
        $validation = Validation::factory($values);
	    $validation->rules('hash', array(array('not_empty'), array('Firefall::check_hash', array($values))));

        $validation->rules('card_id', array(array('not_empty')));
        $validation->rules('code', array(array('not_empty')));
               
        if( ! $validation->check())
        {
            $errors = $validation->errors('1c');
        }
        if(count($errors) == 0)
        {
             $content = serialize($values);
                    
             $event_type = ORM::factory('event_type', Arr::get($values, 'type_id', NULL));
                    
             $log = ORM::factory('log');
             $data = array( 'ip' => Request::$client_ip,
                             //'user_id'  => '',
                    	     // 'target'  => '',	
                    	    'action' => $event_type->code, 	
                    	    'content' => $content, 
                    	    'created' => time(),
                    	    'robot' => $this->get_robot(Request::$client_ip));
             $log->values($data);
                    
             $card_code = Arr::get($values, 'card_id', NULL);
             $user = ORM::factory('user', array('login' => $card_code));
             if(! $user->loaded() AND $card_code)
              {
                  echo ($debag ? '-no_user-'.$card_code.'-': '');
                  $user = ORM::factory('user');
                  $active = Arr::get($values, 'activate', NULL);
                  $active = strtotime($active);
                  /*if(is_string($active) AND intval($active) == 0)
                       {
                            $active = strtotime($active);                     
                       }*/
                  $phone = Arr::get($values, 'phone', 'default phone');
                  $email = Arr::get($values, 'email', $this->next_email());
                  $user_data = array('login'       => $card_code,
                                     'password'    => Text::random('distinct'),
                                     'email'       => $email,
                                     'phone'       => $phone,
                                     'active_time' => $active,
                                     'created' => time(),
                                     );
             
                   $user->values($user_data)->create();
                   //echo '-create_user-'; 
               }
                    
                /**
                  * CHECK NO ACTIVE USER
                */ 
                $role_login = ORM::factory('role', array('name' => 'login'));
                if(! $user->has('roles', $role_login->id))
                { 
                    echo ($debag ? '-user_noreg-' : '');
                    $code = Arr::get($values,'code', NULL);
                    // ADD Checks
                    $check = ORM::factory('check', array('keys' => $code));
                    if(! $check->loaded())
                    {
                      $check = ORM::factory('check');
                    }
                    $created = Arr::get($values, 'created', NULL);
                    $ckeck_data = array('card' => $card_code, 'keys' => $code, 'created' => ($created ? strtotime($created) : time()));
                    $check->values($ckeck_data);
                    $check->save();
                    echo ($debag ? '-create_check-' : '');      
                }
                
                    /**
                    *                                                                          
                    */ 
                    
                    if(in_array($event_type->id, $this->_config_1c['deferred']))
                    {
                        echo ($debag ? '-start_deff-' : '');    
                            //$deferred = ORM::factory('deferred', array('user_id' => $user->id));
                        $keys = Arr::get($values,'code', NULL);
                        
                        ORM::factory('deferred')->clear_tresh($user->id); //                           
                        echo ($debag ? '-clear_deff-' : '' );
                        
                        $deferred = ORM::factory('deferred', array('user_id' => $user->id, 'keys' => $keys));
                        if(! $deferred->loaded())
                        {
                            $deferred = ORM::factory('deferred');
                            $deferred->user = $user;                            
                        }
                        $_key = array('ball','type_id');
                        $_key = array_flip($_key);
                        $values_deferred = array_intersect_key($values, $_key);
                        $values_deferred['keys'] = $keys;
                        
                        $created = Arr::get($values, 'created', NULL);
                        
                        $deferred->created = ($created ? strtotime($created) : time());
                        
                        $active = Arr::get($values, 'activate', NULL);
                        $active = strtotime($active);
                        
                        $deferred->accrual = ($active > 0 ? $active : time() + 24*60*60); //                               
                        $deferred->values($values_deferred)->save();
                    }
                  
                  /*                 */ 
                  echo ($debag ? '--is_log' : '');
                  $log->target = $user->model().'/'.$user->id;
                  $log->create();
                  
                    
                  /**
                  *                  SMS - Email
                  */  
                  if(in_array($event_type->id, $this->_config_1c['subshrieb']))
                  {
                      if($event_type->loaded())
                      {
                             $_config_subshrieb = Kohana::$config->load('subshrieb');
                             $config_subshrieb = $_config_subshrieb->get('default');
                             if(Arr::get($config_subshrieb, 'operation_1c', NULL))
                             {
                               Subshrieb::send($user, '1c/'.$event_type->code, array('ball' => Arr::get($values, 'ball', NULL)));
                             }   
                      }
                  }
                  return TRUE;
          }
          else
          {
              return $errors;
          }
        }
		catch (ORM_Validation_Exception $e)
		{
			$errors = $e->errors('1c');
            $log = ORM::factory('log');
            $content = serialize($values);
            $data = array( 'ip' => Request::$client_ip,
                                   //'user_id'  => '',
                    	   'target'  => 'operation/'.Arr::get($values,'code'),	
                    	   'action' => 'error_1c', 	
                    	   'content' => $content, 
                    	   'created' => time(),
                           'errors' => $errors,
                    	   'robot' => $this->get_robot(Request::$client_ip));
           $log->values($data)->create();
           return $errors;           
		} 
	}
    
    public function  action_generate_hash()
    {
        if ( ! $this->request->is_ajax())
		{
		    throw new HTTP_Exception_403();
		}
        $values = $this->request->post();
        $hash = Firefall::encryption($values);
        exit(json_encode(array('html' => $hash)));
    }
    
    /**
    * get name robot 1C
    */
    
    protected function get_robot($ip = NULL)
    {
        if(! $ip)
        {
          return FALSE;  
        }
        $config = Kohana::$config->load('1c');
        $ips = $config->get('ips');
        if($key = array_search($ip,$ips))
        {
           return $key;
        }
        
        return FALSE;
    }
    
    public static function check_card($id = NULL)
    {
        if(!$id)
        {
            return false;
        }
        return ORM::factory('card', array('code' => $id))->loaded();
    }
    
    /**
    *                                       emal
    */
    protected function next_email()
    {
        $config = Kohana::$config->load('site');
        $domain = $config->get('domain', 'site.ru');
        
        $last_user = ORM::factory('user')->order_by('id', 'desc')->limit(1)->find();
        if($last_user->loaded())
        {
            $last_id = $last_user->id;
        }
        else
        {
            $last_id = Text::random(NULL, 12);
        }
        return 'user_'.$last_id.'@'.$domain;
    }
}