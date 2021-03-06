<?php defined('SYSPATH') or die('No direct script access.');

class Model_Event extends ORM {

protected $_table_name = 'events';


protected $_belongs_to = array(
		/**
		 *  card                                      
		 */
		'card'		=> array(
			'model'		=> 'card',
		),
        'user'		=> array(
			'model'		=> 'user',
		),
        'type'		=> array(
			'model'		=> 'event_type',
		),
	);
    
/*protected $_has_many = array(
        'events' => array(
            'through' => 'cards_events'),
);
*/

public function rules()
	{
		return array(
			'code' => array(
				array('not_empty'),
				array('max_length', array(':value', 64)),
				array(array($this, 'unique'), array('code', ':value')),
			),
            'ball' => array(
				array('is_integer'),
			),
		);
	}
    
public function filters()
	{
		return array(
			'ball' 	=> array(
				array('intval'),
			),
            
            /*'active_time' 	=> array(
				array('strtotime'),
			),*/
		);
	}

public function create(Validation $validation = NULL)
{
    $event = parent::create($validation);
    $event->callback_add();
    return $event;
}

/**
*      active 	        