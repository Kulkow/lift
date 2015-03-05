<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Lift extends Controller_Admin_Layout
{

    public function before(){
		parent::before();
	}

	public function action_index(){
        if ($total = ORM::factory('lift')->count_all()){
            $per_page = 12;
            $page = $this->request->param('page', 1);
            $paging = Paging::make($total, $page, 10, 4, 'admin/נשדûף');
            $lifts = ORM::factory('lift')->order_by('number', 'desc')
            	->offset(($page - 1) * $per_page)->limit($per_page)->find_all();
            
        }
        $filters = Model_Filter::instance('lift', array('filters' => array('id', 'created', 'active'),
                                      'orders' => array('id', 'created', 'active'))
                                      );
                             
        $this->template->content = View::factory('admin/lift/list')->bind('lifts', $lifts)->bind('paging', $paging);
	}

	public function action_add(){
	   $this->action_edit();
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