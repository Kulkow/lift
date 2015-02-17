<?php defined('SYSPATH') or die('No direct script access.');

class Model_Lift extends ORM {
    
    /**
    * fields
    * current - ���� �� ������� ���������� ����
    * level - ���� �� ������� ���� ���� ����
    * status - ���������� � ������� ���������� ����
    * number - ����� ����� � ����. 
    * levels  - ������� � ����� ������
    * dereffeds - ���������� ����� - ��� ��� �������� ����� ����
    * _levels - ����� ��� ��������� (����� ���� ��������������� �� ����� 3 ��� - �� ���� � �� �������� ������ )  
    */
    
    public $current = 1;
    public $level = 1;
    public $status = 0;
    public $levels = [];
    public $_levels = [];
    public $dereffeds = [];

    protected $_table_name = 'lift';
    
    protected $_belongs_to = array(
    		'house'		=> array(
    			'model'		=> 'house',
    		),
    );
        
    protected $_has_many = array(
          'requests'    => array(
                   'model'       => 'request',
                   'foreign_key' => 'lift_id',
               )
      );
    
    
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
    
    public function url_admin($action){
		return Site::url('/admin/lift/'.$action.'/'.$this->id);
	}

	public function url(){
		return Site::url('/lift/'.$this->id.'');
	}
    
    /**
    * Initialisation
    * Rand level if no instance
    */
    public function ini(){
        if($this->loaded()){
            $change = FALSE;
            if(! $this->level){
                $house_level = $this->house->level;
                $this->level = rand(1,$house_level);
                $change = TRUE;
            }
            // ��-��������� ���� �������� 
            if(! $this->status){
                $this->status = 'free';
                $change = TRUE;
            }
            if($change){
                $this->save();
            }
        }    
        return $this;
    }
    
    /**
    * �������� ������� ������� 
    */
    public function check_request(){
        if($this->loaded()){
            //ORM::factory('request')->find(array() 'lift');
            $request = DB::query(Database::SELECT, 'SELECT `id` FROM `request` WHERE `lift_id`=:lift AND `created`<:time AND status !=:status LIMIT 1')
                ->param(':lift', $this->id)
                ->param(':time', time())
                ->param(':status', 'close');
           $rows = $request->execute();
           $row = $rows->current();
           $request_id = Arr::get($row,'id', FALSE);
           return $request_id;
        }
        return FALSE;
    }
    
    /**
    *
    */
    public function check_lift(){
        
    } 
    
    /**
    * ����� �����
    */
    public function request($request = NULL){
        if($request instanceof ORM){
            if(! $request->loaded() OR ! $request->lift->loaded()){
                return FALSE;
            }
            // ���� �� ����
            if($this->status == 0){
                // �� ���� ����, � ��������� ���� �� ������
                $this->level = $request->level;
                $this->status = 1;
                if($this->current > $this->level){
                    $this->direction = 'down';
                }else{
                    $this->direction = 'up';
                } 
                print_R($this->as_array());
                $this->save();   
            }
            
        }else{
            return FALSE;
        }
    }    
    
    public function status(){
        if (! $this->last_event)
        {
            return false;
        }
        $event = ORM::factory('event', $this->last_event);
        return View::factory('admin/event/preview')->bind('event', $event)->render();
    }



}
 