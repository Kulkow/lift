<?php defined('SYSPATH') or die('No direct script access.');

class Model_Lift extends ORM {
    
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
    * вызов лифта
    */
    public function _request($request = NULL){
        if($request instanceof ORM){
            if(! $request->loaded() OR ! $request->lift->loaded()){
                return FALSE;
            }
            // лифт не едет
            if($this->status == 0){
                // то едем туда, и блокируем лифт на вызовы
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
    * Список запросов лифта в зависимости от направления. 
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
                  $this->status = 1; // должен ехать вниз
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
        if($status == 0){
           // если лифт свободен, то обновляем этаж - на который ему ехать
           //$this->status = 'open';
           $this->level = $request->level;
           $this->status = 1;
           // обновим статус лифта
           if($this->current > $request->level){
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
           $lift = $this->as_array();
           return $lift;  
        }else{
            // поиск свободных лифтов 
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
        * Лифт ждет на этаже 
        */
        if($this->status == 0){
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
    */
    public function update_request($level, $status = 'close'){
        if($this->loaded()){
            //лифт приехал на этаж, обновил запросы на этом этаже, если они были 
            $query = DB::update('request')->set(array('status' => $status))->where('level', '=', $level)->where('lift_id', '=', $this->id)->where('status', '!=', $status);
            $request = $query->execute();//->as_array();
            return $request; // если вызов лифта на этом этаже был то вернет array()
        }
        return false;
    }    
    
    /**
    * Проверка наличия заказов 
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
       // вернем разность 
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
 