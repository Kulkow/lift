<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Layout
{
	public $user;
    public $shot = NULL;
    
    public $menu_user = 'main';
    public $side_menu = 'profile';
    
    public function before()
	{
        parent::before();
        $this->user = ORM::factory('user', array('login' => $this->request->param('id')));
        if(! $this->user->loaded())
        {
            $this->error(404);
        }
        if(! $this->user->allow($this->request->action()))
        {
            $this->error(403);
        }
        
       $this->site->assign($this->user->fullname());
	    
       $this->template->bind_global('shot', $this->shot);
       $this->template->bind_global('user', $this->user);
       $this->template->bind_global('menu_user', $this->menu_user);
       $this->template->bind_global('side_menu', $this->side_menu);
       $this->template->content = View::factory('user/view');
       
       $this->sidebar = TRUE;
       
       $this->side_menu = $this->request->action();
        	   
	}
    
    public function action_index()
	{

		$this->shot = View::factory('user/main');
		/**
		 * SEO
		 */
		$this->site->assign($this->user->fullname());
	}
    
    /*public function action_events()
	{
	  $this->shot = View::factory('user/events'); 
	}*/
    
    
    public function action_setting()
	{
      $this->action_profile(); 
	}
    
    public function action_profile()
	{
        $this->menu_user = 'profile';
        
        $this->menu = 'setting';
         
        $_key = array('email','phone');
        $_key = array_flip($_key);
        $old_data = array_intersect_key($this->user->as_array(), $_key);
        $save = NULL;
        if (HTTP_Request::POST == $this->request->method())
		{
			try
			{
				$values = $this->request->post();
                $new_data = array_intersect_key($values, $_key);
                $diff = array_diff ($old_data, $new_data);
				$this->user->values($values)->update();
                if(! empty($diff))
                {
                   /**
                   * SEND OLD EMAIL PHONE
                   */
                   Subshrieb::send($this->user, 'edit', array(),$diff);
                }
                Subshrieb::send($this->user, 'edit');
                $save = 1;
			}
			catch (ORM_Validation_Exception $e)
			{
				$_REQUEST = Arr::merge($_REQUEST, $values);
				$errors = $e->errors('user');
			}
		}
		else
		{
			$_REQUEST = Arr::merge($_REQUEST, $this->user->as_array());
		}

      $this->shot = View::factory('user/setting')->bind('errors', $errors)->bind('save', $save); 
	}
    
    static function check_password($value)
    {
      return Auth::instance()->check_password($value);
    }
    
    public function action_new_password()
	{
	  	$this->menu_user = 'new_password';
        
        $this->menu = 'setting';
        
        if (HTTP_Request::POST == $this->request->method())
		{
			try
			{
               $post = $this->request->post();
               $validation = Validation::factory($post);
               $validation->rules('oldpassword', array(array('not_empty'),array(array($this,'check_password'))));
               $validation->rules('password', array(array('not_empty'), array('min_length', array(':value', 6)),array('equals', array(':value', Arr::get($post, 'confirmpassword', NULL)))));
               $this->user->password = $this->request->post('password');
               $this->user->update($validation);
               
               //$this->template->content = View::factory('auth/password')->bind('password',$post['new_password']);
                
                Subshrieb::send($this->user, 'new_password', array('password' => $post['password']));
                $this->template->bind_global('password', $post['password']);
                //Controller::redirect($this->user->url('setting'));
			}
			catch (ORM_Validation_Exception $e)
			{
				$_REQUEST = Arr::merge($_REQUEST, $post);
				$errors = $e->errors('user');
			}
		}
		else
		{
			$_REQUEST = Arr::merge($_REQUEST, $this->user->as_array());
		}

        $this->shot = View::factory('user/setting/new_password')->bind('errors', $errors); 
	}
    
    public function action_order()
	{
	   $this->shot = View::factory('user/order'); 
	}
    
}