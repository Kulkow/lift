<?php defined('SYSPATH') or die('No direct script access.');

class Model_Lift extends ORM {
    
    /**
    * fields
    * current - ���� �� ������� ���������� ����
    * level - ���� �� ������� ���� ���� ����
    * status - ���������� � ������� ���������� ����
    * 0 - free
    * 1 - go - norequest
    * 2 - open
    * number - ����� ����� � ����. 
    * levels  - ������� � ����� ������
    * dereffeds - ���������� ����� - ��� ��� �������� ����� ����
    * _levels - ����� ��� ��������� (����� ���� ��������������� �� ����� 3 ��� - �� ���� � �� �������� ������ )  
    */
    
  
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
			)
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
    
    public function save(Validation $validation = NULL)
	{
		$this->updated = time();
		return parent::save($validation);
	}
    
       
    /**
    *
    */
    public function check_lift(){
        
    } 
    
    /**
    * ����� �����
    */
    public function _request($request = NULL){
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
                $this->save();   
            }
            
        }else{
            return FALSE;
        }
    }    
   
    
    /**
    * ������ �������� ����� � ����������� �� �����������. 
    */
    public function request($type = 'up'){
        if($this->loaded()){
            switch($type){
                case 'down':
                    $m = '<';
                break;
                
                default:
                    $m = '>';
                break;
            }
            $dRequest = DB::query(Database::SELECT, 'SELECT `id`, created FROM `request` WHERE `lift_id`=:lift AND level '.$m.':level AND `created`<:time AND status !=:status ORDER BY created ASC ')
                ->param(':lift', $this->id)
                ->param(':level', $this->level)
                ->param(':time', time())
                ->param(':status', 'close');
           $requests = $dRequest->execute();
           $ids = array();
           foreach($requests as $request){
                $ids[] = $request['id'];
           }
           return $ids;
        }   
        return array();
	}
    
    public function url_admin($action){
		return Site::url('/admin/lift/'.$action.'/'.$this->id);
	}

	public function url($action = NULL){
		return Site::url('/lift/'.$this->id.($action ? '/'.$action : ''));
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
                $this->current = $this->level;  
                $change = TRUE;
            }
            
            if(! $this->current){
                $this->current = 1;
                $change = TRUE;
            }
            
			if($this->current != $this->level){
                  $this->status = 1; // ������ ����� ����
                  $change = TRUE;    
				  echo $this->current.' = '.$this->level;
				  echo 'no lift '.$this->id;
            }
			
            if($change){
                $this->save();
            }
        }    
        return $this;
    }
    
    /**
    * ���������/��������� ��������� ����� 
    */
    public function status($status = NULL){
        if($this->loaded()){
            if(! $status){
                return $this->status;                
            }
            else{
                $this->status = $status;
                $this->save();
                return $this->status; 
            }
        }
        return FALSE;
    }
    
    // ������� ����
    public function add_request(ORM $request){
        if(! $request->loaded()){
            return FALSE;
        }
        if(! $this->loaded()){
            return FALSE;
        }
        $status = $this->status();
        $_data = array();
        if($status == 0){
           // ���� ���� ��������, �� ��������� ���� - �� ������� ��� �����
           //$this->status = 'open';
           $this->level = $request->level;
           $this->status = 1;
           // ������� ������ �����
           if($this->current > $request->level){
                $this->direction = 'down'; // ���� ����
           }
           else{
                $this->direction = 'up'; // ���� �����
           }
           try{
                $this->save();
           }catch (ORM_Validation_Exception $e){
				$errors = $e->errors('lift');
                return $errors;
			}
           $lift = $this->as_array();
           return $lift;  
        }else{
            // ����� ��������� ������ 
        }
        $lift = $this->as_array();
        return $lift;
    }
    
	/** 
	* ��������� ������ 
	* 
	*/
	public function update_status(){
		$config = Kohana::$config->load('lift');
		if(! $this->loaded()){
			return FALSE;
		}
		$change = FALSE;
		//���� ���� ����� ������ � �� ���� ��, ������
		$params = $config->get('open');
        $_opentime = Arr::get($params, 'time', FALSE);
        $updated = intval($this->updated);
        if(! $updated){
            $change = TRUE;
        }
        /**
        * ���� ���� �� �����
        */
        if($this->status == 2){
            $opentime = time() - intval($this->updated);
    		if($_opentime){
    			if($opentime > $_opentime){
    				$this->status = 0;
    				$change = TRUE;
    			}
    		} 
        }
        /**
        * ���� ���� �� ����� 
        */
        if($this->status == 0){
            // ���� �� ��� ������
            $request = $this->check_request();
            if($request){
                $this->add_request($request);
            }
        }
		
		if($change){
			$this->save();
		}
		return $this;
	}
	
		
		
    /**
    * ��������� ������ �� ���� �����
    */
    public function update_request($level, $status = 'close'){
        if($this->loaded()){
            //���� ������� �� ����, ������� ������� �� ���� �����, ���� ��� ���� 
            $query = DB::update('request')->set(array('status' => $status))->where('level', '=', $level)->where('lift_id', '=', $this->id)->where('status', '!=', $status);
            $request = $query->execute();//->as_array();
            return $request; // ���� ����� ����� �� ���� ����� ��� �� ������ array()
        }
        return false;
    }    
    
    /**
    * �������� ������� ������� 
    */
    public function check_request(){
        if($this->loaded()){
           /*$request = DB::query(Database::SELECT, 'SELECT `id` FROM `request` WHERE `lift_id`=:lift AND status !=:status ORDER BY created ASC LIMIT 1')
                ->param(':lift', $this->id)
                ->param(':status', 1);
           $rows = $request->execute();
           $row = $rows->current();
           $request_id = Arr::get($row,'id', FALSE);
           return $request_id;*/
           $request = ORM::factory('request')->where('lift_id', '=', $this->id)->and_where('status', '!=', 1);
           $request = $request->order_by('created','ASC')->limit(1)->find();
           if(! $request->loaded()){
            return FALSE;
           }
           return $request; 
        }
        return FALSE;
    }
    
    /**
    * �������� ������� ����� �� 1 ����� (��� �� ���� ����) 
    */
    public function check_first_level($house = NULL){
        if(! $house){
            $house = $this->house->id;
        }
        if($house){
            $lift = ORM::factory('lift')->where('house_id', '=', $house)->and_where('level', '=', 1);
        }
        return TRUE;
    }
    
    /**
    * �������� ������� �����
    */
    public function check_status(){
       if(! $this->loaded()){
          return FALSE;
       }
       return $this->status;
    } 
    
    /**
    * ��������/�������� ����
    */
    public function lift_step($n, $step = 1){
       if(! $this->loaded()){
          return FALSE;
       }
       if($n > 0 AND $this->current > $this->level){
            return FALSE;
       }
       if($n < 0 AND $this->current < $this->level){
            return FALSE;
       }
       if($this->current == $this->level){
            $this->status = 'open';
            $this->save();
            return FALSE;
       }
       if($n > 0){
            $this->status = 'up';
       }else{
            $this->status = 'down';
       }
       
       $this->current = $this->current + $n;
       $this->save();
       // ������ �������� 
       return abs(intval($this->current - $this->level));
    }
    
    public function lift($level){
       if(! $this->loaded()){
          return FALSE;
       }
       if($this->status == 0){
            if($this->current == $level){
                return FALSE;
            }
            $this->level = $level;
            $this->status = 1;
            if($this->current > $this->level){
                $this->direction = 'down'; // ���� ����
            }
            else{
                $this->direction = 'up'; // ���� �����
            }
            try{
                $this->save();
            }catch (ORM_Validation_Exception $e){
                $errors = $e->errors('lift');
                return $errors;
            }
       }
       return $this;
    }
    
    /**
    * ��������� (�������) ����� �����
    */
    public function last_request($level = NULL){
       if(! $this->loaded()){
          return FALSE;
       }
       /*
       $request = DB::query(Database::SELECT, 'SELECT `id` FROM `request` WHERE `lift_id`=:lift AND status !=:status ORDER BY created ASC LIMIT 1')
            ->param(':lift', $this->id)
            ->param(':status', 1);
       $rows = $request->execute();
       $row = $rows->current();
       $request_id = Arr::get($row,'id', FALSE);*/
       $request = ORM::factory('request')->where('lift_id', '=', $this->id)->and_where('status', '!=', 1);
       if($level){
          $request = $request->and_where('level', '=', $level);
       }
       $request = $request->order_by('created','ASC')->limit(1)->find();
       if(! $request->loaded()){
        return FALSE;
       }
       return $request; 
    }      
    



}
 