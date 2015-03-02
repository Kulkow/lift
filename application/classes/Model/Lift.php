<?php defined('SYSPATH') or die('No direct script access.');

class Model_Lift extends ORM {
    
    const LIFT_FREE = 0;
    const LIFT_LIFT = 1;
    const LIFT_OPEN = 2;
    const LIFT_BLOCKED = 3;
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
    
    public function rules(){
		return array();
	}
   
    public function save(Validation $validation = NULL){
		$this->updated = time();
		return parent::save($validation);
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
			if($this->current != $this->level AND $this->status != self::LIFT_LIFT){
                  $this->status = self::LIFT_LIFT; // ������ ����� ����
                  $change = TRUE;    
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
    
    /** ���������� ������� ������ �������� ���� */
    public function distance($status = NULL){
        if($this->loaded()){
            return abs(intval($this->level) - intval($this->current));
        }
        return 0;
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
        if($status == self::LIFT_FREE){
           // ���� ���� ��������, �� ��������� ���� - �� ������� ��� �����
           $this->level = $request->level;
           $this->status = self::LIFT_LIFT;
           // ������� ������ �����
           if($this->current > $request->level){
                $this->direction = 'down'; // ���� ����
           }
           else{
                $this->direction = 'up'; // ���� �����
           }
           try{
                $this->save();
                $distance = $this->distance();
                ORM::factory('log')->add_event(NULL, 'go.'.$this->direction, $this, array('level' => $this->level, 'distance' => $distance));
           }catch (ORM_Validation_Exception $e){
				$errors = $e->errors('lift');
                return $errors;
			}
           $lift = $this->as_array();
           return $lift;  
        }else{
            // ����� ��������� ������ 
            $free = $this->free($request->level);
            if($free){
                $free->level = $request->level;
                $free->status = self::LIFT_LIFT;
                // ������� ������ �����
                if($free->current > $request->level){
                    $free->direction = 'down'; // ���� ����
                }
               else{
                    $free->direction = 'up'; // ���� �����
               }
               try{
                    $free->save();
                    $distance = $this->distance();
                    ORM::factory('log')->add_event(NULL, 'go.'.$free->direction, $free, array('level' => $free->level, 'distance' => $distance));
               }catch (ORM_Validation_Exception $e){
    				$errors = $e->errors('lift');
                    return $errors;
    			}
               $lift = $free->as_array();
               return $lift;  
            }
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
        if($this->status == self::LIFT_OPEN){
            $opentime = time() - intval($this->updated);
    		if($_opentime){
    			if($opentime > $_opentime){
    				$this->status = self::LIFT_FREE;
    				$change = TRUE;
    			}
    		} 
        }
        /**
        * ���� ���� �� ����� 
        */
        if($this->status == self::LIFT_FREE){
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
    * �� ������������
    */
    public function update_request($level, $status = NULL){
        if($this->loaded()){
            //���� ������� �� ����, ������� ������� �� ���� �����, ���� ��� ���� 
            $query = DB::update('request')->set(array('status' => $status))->where('level', '=', $level)->where('lift_id', '=', $this->id)->where('status', '!=', $status);
            $request = $query->execute();//->as_array();
            return $request; // ���� ����� ����� �� ���� ����� ��� �� ������ array()
        }
        return false;
    }    
    
    /**
    * �������� ������� ���������� �������� 
    */
    public function check_request(){
        if($this->loaded()){
           $request = ORM::factory('request')->where('lift_id', '=', $this->id)->and_where('status', '=', Model_Request::REQUEST_DEFENDER);
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
            $this->status = self::LIFT_OPEN;
            $this->save();
            return FALSE;
       }else{
            $this->status = self::LIFT_LIFT;
       }
       if($n > 0){
            $this->direction = 'up';
       }else{
            $this->direction = 'down';
       }
       
       $this->current = $this->current + $n;
       $this->save();
       // ������ �������� 
       return abs(intval($this->current - $this->level));
    }
    
    /**
    * ����� ��������� ������
    * ������� ����� �����
    * � �����
    */
    public function free($level = NULL, $house = NULL){
        $lift = NULL;
        $diff = 1000;
        if($this->loaded()){
            $house = $this->house->id; 
        }
        if($house){
            $lifts = ORM::factory('lift')->where('house_id', '=',$house)->or_where_open()->or_where('status', '=',self::LIFT_FREE)->or_where('status', '=',self::LIFT_OPEN)->or_where_close()->order_by('level', 'DESC')->find_all(); 
            foreach($lifts as $_lift){
                if($_lift->level == $level){
                    return $_lift;
                }
                $_diff = abs($level - $_lift->level);
                if($_diff < $diff){
                    $diff = $_diff;
                    if($_lift->status == self::LIFT_FREE){
                        $lift = $_lift;    
                    }
                }
            } 
            return  $lift; 
        }
    }
    
    public function lift($level){
       if(! $this->loaded()){
          return FALSE;
       }
       if($this->status == self::LIFT_FREE){
            if($this->current == $level){
                return $this;
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
       if(! $level){
        return FALSE;
       }
       $request = ORM::factory('request')->where('house_id', '=', $this->house->id)->and_where('status', '=', Model_Request::REQUEST_NEW);
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
 