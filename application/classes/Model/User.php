<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_User extends Model_Auth_User {
    
    /**
	 * Labels for fields in this model
	 *
	 * @return array Labels
	 */
	public function labels()
	{
		return array(
			'username'         => '        ',
			'email'            => 'Email',
			'password'         => '     ',
            'name'             => '   ',
            'address'          => '     ', 
		);
	}
    
    
    public static function valid_contact($user)
    {
       if($user instanceof ORM)
       {
          if(! empty($user->email) OR ! empty($user->phone))
          {
             RETURN TRUE;
          }
       }
       RETURN FALSE;
    }
    
    public function rules()
	{
		return array(
			'login' => array(
				array('not_empty'),
				array('max_length', array(':value', 32)),
				array(array($this, 'unique'), array('login', ':value')),
                
			),
			'password' => array(
				array('not_empty'),
			),
			'email' => array(
				array('not_empty'),
				array('email'),
				array(array($this, 'unique'), array('email', ':value')),
                //array(array($this, 'valid_contact'), array($this)),
			),
            'phone' => array(
				//array('not_empty'),
				//array('phone'),
			),
		);
	}
    
    public function url_admin($action)
	{
		return Site::url('/admin/user/'.$action.'/'.$this->id);
	}

	public function url($action = NULL)
	{
		return Site::url('/user/'.$this->login.'/'.$action);
	}
    
    
    public function fullname()
	{
	   return ($this->name ? $this->name : $this->login);
	}
    
    public function allow($action)
    {
        $auth_user = Auth::instance()->get_user();
        if(! $auth_user)
        {
           return FALSE;    
        }
        //print_r($auth_user);
        //print_r($this);
        if($this->login == $auth_user->login)
        {
           return TRUE;   
        }
        return FALSE;
    }
    
    public function check_create($login = NULL)
    {
        if($login)
        {
            $user = ORM::factory('user', array('login' => $login));
            if($user->loaded())
            {
                $role_login = ORM::factory('role', array('name' => 'login'));
                if(! $user->has('roles', $role_login->id))
                {
                    echo 'UPDATE';
                    return $user;
                } 
            }
        }
        return ORM::factory('user');
    }
    
    public function balance()
    {
        
        $card_id = intval($this->login);
        $bonus = ORM::factory('bonus')->where('ID', '=', $card_id)
                                      ->and_where('Active', '=', 1)->find_all();
                                      
        $balanse = 0;
        foreach($bonus as $operation)
        {
            //echo 'D-'.$operation->DEBKRED.'--o-'.$operation->Operation.'--'.$operation->sumBonus.'<br/>';
            if($operation->DEBKRED == 1)
            {
               $balanse = $balanse + $operation->sumBonus;
            }
            if($operation->DEBKRED == 0)
            {
              $balanse =  $balanse - $operation->sumBonus;
            }
            //$balanse =  Model_Bonus::calculate($operation->sumBonus, $operation->Operation, $balanse);
        }
        return $balanse;
        
    }
    
    public function delete()
    {
        if(! $this->loaded())
        {
            return FALSE;
        }
        if($this->login == 'admin')
        {
            return FALSE;
        }
       parent::delete();
    }
	
}