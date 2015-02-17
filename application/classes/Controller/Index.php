<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Index extends Controller_Layout
{
    
    public function action_index()
	{
       $house = ORM::factory('house', array('level' => 10));
       $lifts = $house->lifts->find_all();
       foreach($lifts as $lift){
            $lift->ini();
       }
       $this->template->bind_global('house', $house);
       $this->template->bind_global('lifts', $lifts);
       $this->template->content = View::factory('house/view');
	}
}