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
        $defender = ORM::factory('request')->defender($house);
        $defender_level = NULL;
        if(! empty($defender)){
            $defender_level = array_keys($defender);
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
            if($_lift->status == $_lift::LIFT_FREE){
                if($defender_level){
                    $_defender_level = $this->near($defender_level, $_lift->current);
                    $_defender = $defender[$_defender_level];
                    $_lift->add_request($_defender);
                    $_key = array_search($_defender_level, $defender_level);
                    if($_key){
                        unset($defender_level[$_key]);                        
                    }
                }
            }
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
    
    // самый близких 
    protected function near($levels = [], $current = NULL){
        $min = NULL;
        $_level = NULL;
        foreach($levels as $level){
            $_min = abs($current - $level);
            if($min){
                if($_min < $min){
                    $min = $_min;
                    $_level = $level;
                }
            }else{
                $min = $_min; 
                $_level = $level;
            }
        }
        return $_level;
    }

}