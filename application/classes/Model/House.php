<?php defined('SYSPATH') or die('No direct script access.');

class Model_House extends ORM {

    protected $_table_name = 'house';
    
    /*
    protected $_belongs_to = array(
    		'user'		=> array(
    			'model'		=> 'user',
    		),
    	);
        
    /*protected $_has_many = array(
            'events' => array(
                'through' => 'cards_events'),
    );*/
    
    protected $_has_many = array(
          'lifts'    => array(
                   'model'       => 'lift',
                   'foreign_key' => 'house_id',
               )
      );
    
    
    public function rules(){
		return array(
			'level' => array(
				array('not_empty'),
			),
            'number' => array(
				array('not_empty'),
			),
		);
	}
    
    public function filters(){
		return array(
           'level' 	=> array(
				array('intval'),
			),
		);
	}
    
    public function url_admin($action){
		return Site::url('/admin/house/'.$action.'/'.$this->id);
	}

	public function url(){
		return Site::url('/house/'.$this->login.'');
	}
    
    public function levels($end = 1, $step = 1){
        $count = $this->level; 
        for ($i = $count; $i >= $end; $i -= $step) {
            yield $i;
        }
    }
}    
    