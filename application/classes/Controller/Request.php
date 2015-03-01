<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Request extends Controller_Layout
{
    public $request;
    
    public function before(){
		parent::before();
	}

	public function action_index(){
        if ($total = ORM::factory('lift')->count_all()){
            $per_page = 12;
            $page = $this->request->param('page', 1);
            $paging = Paging::make($total, $page, 10, 4, 'admin/рщгыу');
            $lifts = ORM::factory('lift')->order_by('number', 'desc')
            	->offset(($page - 1) * $per_page)->limit($per_page)->find_all();
            
        }
        $filters = Model_Filter::instance('lift', array('filters' => array('id', 'created', 'active'),
                                      'orders' => array('id', 'created', 'active'))
                                      );
                             
        $this->template->content = View::factory('admin/lift/list')->bind('lifts', $lifts)->bind('paging', $paging);
	}

	/**
    * Вызов лифта
    */
    public function action_add(){
       $post = $this->request->post();
       $lift_id = Arr::get($post, 'lift', NULL);
       $_level = Arr::get($post, 'level', NULL);
       if(! $lift_id){
            return $this->error('request.nolift');
       }
       $lift = ORM::factory('lift', $lift_id);
       if(! $lift->loaded()){
            return $this->error('request.lift.noloaded');
       }
       
       $status =  $lift->status();
       if (HTTP_Request::POST == $this->request->method()){
			try{
                $request = ORM::factory('request');
                $request->values($post);
                $request->lift = $lift; // может не важно какой лифт поедет, главное чтобы он приехал
                $request->user = $this->auth_user();
                
                $free_lift = $lift->free($_level); //поиск свободных лифтов
                if($free_lift){
                    $lift =  $free_lift;
                    $request->filter()->save();
                    ORM::factory('log')->add_event($this->auth_user, 'request', $lift, array('level' => $request->level));
                    $l = $lift->add_request($request); // обновим статус лифта 
                }else{
                  $request->status = Model_Request::REQUEST_DEFENDER;
                  $request->filter()->save();
                  $l  = NULL;
                  ORM::factory('log')->add_event($this->auth_user, 'defender', $request, array('level' => $request->level));
                }
                ORM::factory('log')->add_event($this->auth_user, 'request', $lift, array('level' => $request->level));
                $l = $lift->add_request($request); // обновим статус лифта
                if ($this->request->is_ajax()){
        			exit(json_encode(array('lift' => $l, 'request' => $request->as_array()))); // здесь будем статус лифта до вызова
        		}
        		else{
                    if(Arr::get($l,'status', NULL) == LIFT_FREE){
                        // лифт едет к нам
                        Controller::redirect($lift->url('lift'));
                    }
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
		else{
			$_REQUEST = Arr::merge($_REQUEST, $lift->as_array());
		}
        if (! $this->request->is_ajax()){
		  $this->template->content = View::factory('admin/lift/edit')->bind('errors', $errors);
        }
	}
}