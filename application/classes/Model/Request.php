<?php defined('SYSPATH') or die('No direct script access.');

/**
* ������ ������� �����
*/

class Model_Request extends ORM {
    
    /**
    * fields
    * level - ���� �� ������� ���������� ����
    * status - ���������� � ������� ���������� ����
    * number - ����� ����� � ����. 
    */

    protected $_table_name = 'request';
    
    protected $_belongs_to = array(
    		'lift'		=> array(
    			'model'		=> 'lift',
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
    
    public function filter(array $filters = array()){
        $this->created = time();
		return parent::filter($filters);
	}
    


}
 