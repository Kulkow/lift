<?php defined('SYSPATH') or die('No direct script access.');

/**
* Модель запроса лифта
*/

class Model_Request extends ORM {
    
    /**
    * fields
    * level - этаж на котором находиться лифт
    * status - соостояние в котором находиться лифт
    * number - номер лифта в доме. 
    */

    protected $_table_name = 'request';
    
    protected $_belongs_to = array(
    		'lift'		=> array(
    			'model'		=> 'lift',
    		),
            'user'		=> array(
    			'model'		=> 'user',
    		),
    );
        
    /*protected $_has_many = array(
          'requests'    => array(
                   'model'       => 'request',
                   'foreign_key' => 'request_id',
               )
      );
    */
    
    public function rules()
	{
		return array(
			/*'code' => array(
				array('not_empty'),
				array('max_length', array(':value', 32)),
				array(array($this, 'unique'), array('code', ':value')),
			),*/
		);
	}
    
    public function filters(){
		return array(
			'level' 	=> array(
				array('intval'),
			),
            'status' 	=> array(
				array('strtolower'),
			),
		);
	}
    
    public function filter(){
        $this->created = time();
        $this->ip = Request::$client_ip;
		//return parent::filters();
        return $this;
	}
    
    /*
    public function create(Validation $validation = NULL){
        $this->filter();
        parent::create($validation);
    }*/
    
    
    
    /**
    *
    */
    public function duble($level, ORM $request){
        $dRequest = DB::query(Database::SELECT, 'SELECT `id` FROM `request` WHERE `lift_id`=:lift AND level =:level AND status !=:status')
                ->param(':lift', $request->lift->id)
                ->param(':level', $level)
                ->param(':status', 'close');
           $requests = $dRequest->execute()->as_array();
           if(! empty($requests)){
                return FALSE;
           }
           return TRUE;
    }
    
    public function close(){
        if($this->loaded()){
            $this->status = 1;
            $this->save();
        }
        return FALSE;
    }
    


}
 