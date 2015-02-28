<?php defined('SYSPATH') or die('No direct script access.');

class Model_Log extends ORM {

    protected $_table_name = 'log';
    
    protected $_belongs_to = array(
		'user'		=> array(
			'model'		=> 'user',
		),
	);
    
    
    public function rules()	{
		return array(
			'event' => array(
				array('not_empty'),
			),
		);
	}
    
    public function filters()
	{
		return array(
           
           /* 'active_time' 	=> array(
				array('strtotime'),
			),*/
		);
	}
    /**
    * Логируем
    * Пользователь
    * 
    * $object - ORM - над которым выполняется действие
    * $target - ORM - над которым выполняется действие
    */
    public function add_event($user = NULL,$event = '', $target = NULL, $content = '')
    {
        $log = ORM::factory('log');
        try{
            $values = array('event' => $event);
            $log->values($values);
            if($target instanceof ORM){
                if($this->loaded()){
                  $log->target = $target->model();
                  $log->target_id = $target->id;  
                }
            }else{
               $log->target = (string)$target; 
            }
            if($user){
                $log->user = $user;
            }
            if(! empty($content)){
                if(! is_array($content)){
                    $content = array($content);
                }
                $log->content = serialize($content);
            }
            $log->ip = Request::$client_ip;
            $log->created = time();
            $log->create();
        }
        catch (ORM_Validation_Exception $e)
        {
    	    $errors = $e->errors('log');
        }
    }
    public function target()
    {
        if($this->loaded() AND $this->target)
        {
            if($this->target AND $this->target_id){
                
            }
        }
        return $this->target;
    }
}    
    