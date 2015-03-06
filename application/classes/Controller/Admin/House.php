<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_House extends Controller_Admin_Layout
{

    public function before(){
		parent::before();
	}

	public function action_index(){
        if ($total = ORM::factory('house')->count_all()){
            $per_page = 12;
            $page = $this->request->param('page', 1);
            $paging = Paging::make($total, $page, 10, 4, 'admin/נשדûף');
            $houses = ORM::factory('house')->order_by('number', 'desc')
            	->offset(($page - 1) * $per_page)->limit($per_page)->find_all();
            
        }
        $filters = Model_Filter::instance('house', array('filters' => array('id', 'created', 'active'),
                                      'orders' => array('id', 'created', 'active'))
                                      );
                             
        $this->template->content = View::factory('admin/house/list')->bind('houses', $houses)->bind('paging', $paging);
	}

	public function action_add(){
	   $this->action_edit();
       //Controller::redirect('admin/house/add');
	}

	public function action_edit(){
		$house = ORM::factory('house', $this->request->param('id'));
        /*if ( ! $house->loaded()){
        	throw new HTTP_Exception_404();
		}*/
		if (HTTP_Request::POST == $this->request->method()){
			try{
				$values = $this->request->post();
				$house->values($values)->save();
                Controller::redirect('admin/house');
			}
			catch (ORM_Validation_Exception $e){
				$_REQUEST = Arr::merge($_REQUEST, $values);
				$errors = $e->errors('house');
                print_r($errors);
			}
		}
		else{
			$_REQUEST = Arr::merge($_REQUEST, $house->as_array());
		}
		$this->template->content = View::factory('admin/house/edit')->bind('errors', $errors);
	}

	public function action_delete(){
		$house = ORM::factory('house', $this->request->param('id'));
		if ( ! $house->loaded()){
			throw new HTTP_Exception_404();
		}
		$house->delete();
		if ($this->request->is_ajax()){
			echo json_encode(array('error' => FALSE));
			exit();
		}
		else{
            Controller::redirect('admin/house');
		}
	}

}