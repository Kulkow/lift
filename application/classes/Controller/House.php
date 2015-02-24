<?php defined('SYSPATH') or die('No direct script access.');

class Controller_House extends Controller_Layout
{

    public function before(){
		parent::before();
	}

	public function action_index(){
        return FALSE;
	}
    
    /**
    * проверка состояния лифтов 
    */
    public function action_lifts(){
	    $house = $this->request->param('id');
		if(! $house){
			$this->error('no house');
		}
		$lifts = [];
		$ids = [];
		/*
		$rsLifs = DB::query(Database::SELECT, 'SELECT * FROM `lift` WHERE `house_id`=:house_id ORDER BY id ASC ')->param(':house_id', $house);
	    $_lifts = $rsLifs->execute();
		foreach($_lifts as $_lift){
			$lifts[$_lift['id']] = $_lift;
			$ids[] = $_lift['id'];
		}
		*/
		$_lifts = ORM::factory('lift')->where('house_id','=',$house)->find_all();
		foreach($_lifts as $_lift){
			$_lift->update_status();
			$lifts[$_lift->id] = $_lift->as_array();
			$ids[] = $_lift->id;
		}
		
		if ($this->request->is_ajax()){
			exit(json_encode(array('lifts' => $lifts, 'ids' => $ids))); 
		}
		$this->template->content = View::factory('admin/house/view')->bind('lifts', $lifts);
	}

}