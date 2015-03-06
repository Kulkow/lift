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
                if($target->loaded()){
                  $model = $target->model();
                  if(! $model){
                     $model = get_class($target);
                     $model = str_replace('Model_','',$model);
                     $model = strtolower($model);
                  }
                  $log->target = $model;
                  $log->target_id = $target->id;  
                }
            }else{
               $log->target = (string)$target; 
            }
            if($user){
                $log->user = $user;
            }else{
                $auth_user = Auth::instance()->get_user();
                if(! $auth_user){
                    $auth_user = ORM::factory('user', array('login' => 'guest'));
                } 
                $log->user = $auth_user;
            }
            if(! empty($content)){
                if(! is_array($content)){
                    $content = array($content);
                }
                if($distance = Arr::get($content, 'distance', NULL)){
                    $log->distance = $distance;
                }
                if($level = Arr::get($content, 'level', NULL)){
                    $log->level = $level;
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
    