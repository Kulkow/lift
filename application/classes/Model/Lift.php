<?php defined('SYSPATH') or die('No direct script access.');

class Model_Lift extends ORM {
    
    const LIFT_FREE = 0;
    const LIFT_LIFT = 1;
    const LIFT_OPEN = 2;
    const LIFT_BLOCKED = 3;
    /**
    * fields
    * current - этаж на котором находиться лифт
    * level - этаж на которой едет лифт лифт
    * status - соостояние в котором находиться лифт
    * 0 - free
    * 1 - go - norequest
    * 2 - open
    * number - номер лифта в доме. 
    * levels  - нажатые в лифте кнопки
    * dereffeds - отложенные этажи - там где остались ждать люди
    * _levels - этажи для остановки (пусть лифт останавливается не более 3 раз - на пути и на соседних этажах )  
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
                  $this->status = self::LIFT_LIFT; // должен ехать вниз
                  $change = TRUE;    
            }
			
            if($change){
                $this->save();
            }
        }    
        return $this;
    }
    
    /**
    * Получение/изменение состояния лифта 
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
    
    /** Расстояние которое должен проехать лифт */
    public function distance($status = NULL){
        if($this->loaded()){
            return abs(intval($this->level) - intval($this->current));
        }
        return 0;
    }        
    
    // вызвали лифт
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
           // если лифт свободен, то обновляем этаж - на который ему ехать
           $this->level = $request->level;
           $this->status = self::LIFT_LIFT;
           // обновим статус лифта
           if($this->current > $request->level){
                $this->direction = 'down'; // едем вниз
           }
           else{
                $this->direction = 'up'; // едем вверх
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
            // поиск свободных лифтов 
            $free = $this->free($request->level);
            if($free){
                $free->level = $request->level;
                $free->status = self::LIFT_LIFT;
                // обновим статус лифта
                if($free->current > $request->level){
                    $free->direction = 'down'; // едем вниз
                }
               else{
                    $free->direction = 'up'; // едем вверх
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
	* обновляет статус 
	* 
	*/
	public function update_status(){
		$config = Kohana::$config->load('lift');
		if(! $this->loaded()){
			return FALSE;
		}
		$change = FALSE;
		//если лифт долго открыт и не едет то, делаем
		$params = $config->get('open');
        $_opentime = Arr::get($params, 'time', FALSE);
        $updated = intval($this->updated);
        if(! $updated){
            $change = TRUE;
        }
        /**
        * Лифт ждет на этаже
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
        * Лифт ждет на этаже 
        */
        if($this->status == self::LIFT_FREE){
            // Есть ли еще вызовы
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
    * Обновляет запрос на этом этаже
    * не используется
    */
    public function update_request($level, $status = NULL){
        if($this->loaded()){
            //лифт приехал на этаж, обновил запросы на этом этаже, если они были 
            $query = DB::update('request')->set(array('status' => $status))->where('level', '=', $level)->where('lift_id', '=', $this->id)->where('status', '!=', $status);
            $request = $query->execute();//->as_array();
            return $request; // если вызов лифта на этом этаже был то вернет array()
        }
        return false;
    }    
    
    /**
    * Проверка наличия отложенных запросов 
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
    * Проверка наличия лифта на 1 этаже (или он туда едет) 
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
    * Проверка статуса лифта
    */
    public function check_status(){
       if(! $this->loaded()){
          return FALSE;
       }
       return $this->status;
    } 
    
    /**
    * Проднять/опустить лифт
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
       // вернем разность 
       return abs(intval($this->current - $this->level));
    }
    
    /**
    * Поиск свободных лифтов
    * которые ближе всего
    * к этажу
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
                $this->direction = 'down'; // едем вниз
            }
            else{
                $this->direction = 'up'; // едем вверх
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
    * последний (Текущий) вызов лифта
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
 