<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Lift extends Controller_Layout
{

    public function before(){
		parent::before();
	}

	public function action_index(){
        return FALSE;
	}
    
    /**
    * проверка состояния заказов 
    */
    public function action_level(){
		$lift = ORM::factory('lift', $this->request->param('id'));
        if ( ! $lift->loaded()){
        	return $this->error('lift.empty');
		}
		if (HTTP_Request::POST == $this->request->method()){
			try{
				$post = $this->request->post();
                $level = Arr::get($post, 'lift', NULL);
                if(! $level){
                    return $this->error('lift.nolevel');
                }
                if($lift->level == $level){
                    $lift->current = $level;
                    $lift->request($level,'close');
                    /**
                    * Проверяем наличие не выполненных запросов
                    */
                }
				$lift->values($values)->save();
                Controller::redirect('admin/lift');
			}
			catch (ORM_Validation_Exception $e){
				$_REQUEST = Arr::merge($_REQUEST, $values);
				$errors = $e->errors('lift');
                print_r($errors);
			}
		}
		else{
			$_REQUEST = Arr::merge($_REQUEST, $lift->as_array());
		}
		$this->template->content = View::factory('admin/lift/edit')->bind('errors', $errors);
	}
    
    /**
    * поднятие спуск лифта на один этаж 
    */
    public function action_lift2(){
		$lift = ORM::factory('lift', $this->request->param('id'));
        if ( ! $lift->loaded()){
        	return $this->error('lift.empty');
		}
        //if (HTTP_Request::POST == $this->request->method()){
            //$post = $this->request->post();
            //$type = Arr::get($post, 'type', NULL);
            $type = $lift->status();
            $go = FALSE;
            $current = $lift->current;
            switch($type){
                case 'up':
                    $go = $lift->lift(1);
                    $current++;
                break;
                
                case 'down':
                    $go = $lift->lift(-1);
                    $current--;
                break;
            }
            if($go === FALSE){
                return FALSE;
            }elseif($go > 0){
                $request = $lift->update_request($current); //обновляет статус запроса на этаже   
                if ($this->request->is_ajax()){
                    $status = ! empty($request) ? 'open' : $lift->status;  
        			exit(json_encode(array('request' => $request, 'level' => intval($lift->level), 'current' => intval($lift->current), 'status' => $status)));
        		}else{
        		  Controller::redirect('lift/lift/'.$this->request->param('id'));
        		}
            }else{
               if ($this->request->is_ajax()){
        			exit(json_encode(array('status' => 'open')));
        		}else{
        		  Controller::redirect('lift/open/'.$this->request->param('id'));
        		} 
            }
        //}
    }
    
    public function action_lift(){
		$lift = ORM::factory('lift', $this->request->param('id'));
        if ( ! $lift->loaded()){
        	return $this->error('lift.empty');
		}
        if (HTTP_Request::POST == $this->request->method()){
            $post = $this->request->post();
            $level = Arr::get($post, 'level', NULL);
            $lift->level = $level;
            if($lift->current == $lift->level){
                
            }else{
               try{
                    $lift->status = 3;
                    if($lift->current > $lift->level){
                        $lift->direction = 'down'; // едем вниз
                    }
                    else{
                        $lift->direction = 'up'; // едем вверх
                    }
                    $lift->update();
                    if ($this->request->is_ajax()){
            			exit(json_encode(array('lift' => $lift->as_array()))); 
            		}
               }
    			catch (ORM_Validation_Exception $e){
    				$_REQUEST = Arr::merge($_REQUEST, $post);
    				$errors = $e->errors('request');
                    if ($this->request->is_ajax()){
                        exit(json_encode(array('errors' => $errors)));
            		}
    			} 
            }
        }
    }
    
    /**
    * открыть лифт
    */
    public function action_open(){
		$lift = ORM::factory('lift', $this->request->param('id'));
        if ( ! $lift->loaded()){
        	return $this->error('lift.empty');
		}
        
        $lift->current = $lift->level; 
        $lift->status = 2;
        $lift->save();
        if ($this->request->is_ajax()){
            exit(json_encode(array('status' => 'open')));
        }
   }
   
    /**
    * отправить лифт на этаж
    */
    public function action_close(){
		$lift = ORM::factory('lift', $this->request->param('id'));
        if ( ! $lift->loaded()){
        	return $this->error('lift.empty');
		}
        if (HTTP_Request::POST == $this->request->method()){
            $post = $this->request->post();
            $level = Arr::get($post,'level', NULL);
            if($level AND $lift->status == 'open'){
                $lift->lift = $level;
                $lift->save();
            }
            $lift->status = '';
            $lift->save();
        }
   }    

	public function action_add(){
	   
       $post = $this->request->post();
       print_r($post);
       $json = json_encode(array('error' => FALSE, 'status' => 'up'));
       exit($json);
       
       $lift_id = Arr::get($post, 'lift', NULL);
       if(! $lift_id){
            return $this->error('request.nolift');
       }
       $lift = ORM::factory('lift', $lift_id);
       if(! $lift->loaded()){
            return $this->error('request.lift.noloaded');
       }
       
       /**
        *  Проверим состояние лифта
       */
       $status =  $lift->status();
       
	   $request = ORM::factory('request');
       if (HTTP_Request::POST == $this->request->method()){
			try{
				$post = $this->request->post();
				$request->values($post)->save();
                //Controller::redirect('admin/lift');
                if ($this->request->is_ajax()){
        			echo json_encode(array('error' => FALSE));
        			exit();
        		}
        		else{
                    Controller::redirect('admin/lift');
        		}
			}
			catch (ORM_Validation_Exception $e){
				$_REQUEST = Arr::merge($_REQUEST, $values);
				$errors = $e->errors('lift');
			}
		}
		else{
			$_REQUEST = Arr::merge($_REQUEST, $lift->as_array());
		}
		$this->template->content = View::factory('admin/lift/edit')->bind('errors', $errors);
       //$this->action_edit();
	}

	public function action_edit(){
		$lift = ORM::factory('lift', $this->request->param('id'));
        /*if ( ! $lift->loaded()){
        	throw new HTTP_Exception_404();
		}*/
		if (HTTP_Request::POST == $this->request->method()){
			try{
				$values = $this->request->post();
				$lift->values($values)->save();
                Controller::redirect('admin/lift');
			}
			catch (ORM_Validation_Exception $e){
				$_REQUEST = Arr::merge($_REQUEST, $values);
				$errors = $e->errors('lift');
                print_r($errors);
			}
		}
		else{
			$_REQUEST = Arr::merge($_REQUEST, $lift->as_array());
		}
		$this->template->content = View::factory('admin/lift/edit')->bind('errors', $errors);
	}

	public function action_delete(){
		$lift = ORM::factory('lift', $this->request->param('id'));
		if ( ! $lift->loaded()){
			throw new HTTP_Exception_404();
		}
		$lift->delete();
		if ($this->request->is_ajax()){
			echo json_encode(array('error' => FALSE));
			exit();
		}
		else{
            Controller::redirect('admin/lift');
		}
	}

}