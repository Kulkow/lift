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
        $first_level_lift = NULL;
        if (ORM::factory('lift')->check_first_level($house)){
            $first_level_lift = TRUE; 
        }
		$_lifts = ORM::factory('lift')->where('house_id','=',$house)->order_by('level', 'ASC')->find_all();
		foreach($_lifts as $_lift){
			$_lift = $_lift->update_status();
            if($first_level_lift){
                $_l = $_lift->lift(1);
                if($_l){
                    $_lift = $_l;
                }else{
                    return $this->error('lift.nolift');
                }
                $first_level_lift = FALSE;
            }
            $lifts[$_lift->id] = $_lift->as_array();
			$ids[] = $_lift->id;
		}
        
		
		if ($this->request->is_ajax()){
			exit(json_encode(array('lifts' => $lifts, 'ids' => $ids))); 
		}
		$this->template->content = View::factory('admin/house/view')->bind('lifts', $lifts);
	}

}