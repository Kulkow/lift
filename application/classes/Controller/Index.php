<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Index extends Controller_Layout
{
    
    public function action_index()
	{
       $house = ORM::factory('house', array('level' => 10));
       $first_level_lift = NULL;
       $min_level = NULL;
       if (ORM::factory('lift')->check_first_level($house->id)){
            $min_level = 10; 
       }
       $lifts = $house->lifts->find_all();
       
       foreach($lifts as $lift){
            $lift->ini();
            if($min_level){
                if($lift->level <= $min_level){
                   $min_level = $lift->level;
                   $first_level_lift = $lift;  
                }
            }
       }
       if($first_level_lift){
           $first_level_lift->lift(1);
       }
       $this->template->bind_global('house', $house);
       $this->template->bind_global('lifts', $lifts);
       $this->template->content = View::factory('house/view');
	}
}