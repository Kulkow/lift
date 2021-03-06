<?php defined('SYSPATH') or die('No direct script access.');

class Model_Subshrieb extends ORM {

    protected $_table_name = 'subshrieb';
    
    
    protected $_belongs_to = array(
    		/**
    		 *                  card
    		 */
    		'user'		=> array(
    			'model'		=> 'user',
    		),
    	);


    //                  default                 
    public function default_create($user, $phone = 1, $email = 1)
    {
        if($user instanceof ORM)
        {
            $default = array();
            if($phone) $default['phone'] = 1;
            if($email) $default['email'] = 1; 
            $this->values($default);
            $this->user = $user;
            $this->create();  
        }
        return $this;
    }
}