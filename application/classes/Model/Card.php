<?php defined('SYSPATH') or die('No direct script access.');

class Model_Card extends ORM {

    protected $_table_name = 'cards';
    
    
    protected $_belongs_to = array(
    		/**
    		 *          card
    		 */
    		'user'		=> array(
    			'model'		=> 'user',
    		),
    	);
        
    /*protected $_has_many = array(
            'events' => array(
                'through' => 'cards_events'),
    );*/
    
    
    public function rules()
	{
		return array(
			'code' => array(
				array('not_empty'),
				array('max_length', array(':value', 32)),
				array(array($this, 'unique'), array('code', ':value')),
			),
		);
	}
    
    public function filters()
	{
		return array(
			/*'code' 	=> array(
				array('strtolower'),
			),*/
            
            'active_time' 	=> array(
				array('strtotime'),
			),
		);
	}
    
    
    public function active()
	{
	   if(! $this->active)
       {
          if(! $this->active_time)
          {
             return t('card.status.empty');
          }
          return t('card.status.active_time', array('active' => date('d.m.Y H:i:s', $this->active_time)));
       }
       return t('card.status.active');
	}
    
    public function url_admin($action)
	{
		return Site::url('/admin/card/'.$action.'/'.$this->id);
	}

	public function url()
	{
		return Site::url('/user/'.$this->login.'');
	}
    
    public function last_event()
    {
        if (! $this->last_event)
        {
            return false;
        }
        $event = ORM::factory('event', $this->last_event);
        return View::factory('admin/event/preview')->bind('event', $event)->render();
    }



}
 