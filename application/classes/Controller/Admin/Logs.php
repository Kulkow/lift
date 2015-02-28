<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Logs extends Controller_Admin_Layout
{

    public $sort = 'created';
    public $up = 'desc';
    
    public $session = NULL;
    public $search = NULL;
    public $logs = NULL;
    
    public function before()
    {
        parent::before();
        $this->session = Session::instance();
        $this->logs = ORM::factory('log');
        
        if (HTTP_Request::POST == $this->request->method()){
		  $values = $this->request->post();
          $field = Arr::get($values,'field', NULL);
          $search = Arr::get($values,'search', NULL);
          switch($field){
             case 'event':
                $search = trim($search);
             break;
             
             case 'target_id':
                $field = 'target_id';
                $search = intval($search);
             break;
             
             case 'user_id':
                $field = 'user_id';
                $search = intval($search);
             break;
             
             case 'level':
             
             break;
             
             default:
                $search = NULL;
                $field = NULL;
             break;
          }
          $this->session->set('field', $field); 
          
          $this->session->set('search', $search);  
		}
        else{
            $field = $this->session->get('field', NULL);
            $search = $this->session->get('search', NULL);
            
        }
        
       if($field AND $search)
       {
          $this->search = array('field' => $field, 'value' => $search);
          //$this->users = $this->users->where($field, 'LIKE', $search.'%');
       }
        
        $this->template->bind_global('search_field',$field);
        $this->template->bind_global('search',$search);
        
    }
    
    public function action_index()
	{
        $per_page = 30;
        $page = $this->request->param('page', 1);
        if(! $this->search){
           $logs = $this->logs->order_by('created', 'desc')
                     ->offset(($page - 1) * $per_page)->limit($per_page)->find_all();
          $total = $this->logs->count_all();                                
        }
        else{
           $logs = $this->logs->where(Arr::get($this->search, 'field'), 'LIKE', '%'.Arr::get($this->search, 'value').'%')
                                ->order_by('created', 'desc')
                                ->offset(($page - 1) * $per_page)->limit($per_page)->find_all();
           $total = $this->logs->where(Arr::get($this->search, 'field'), 'LIKE', '%'.Arr::get($this->search, 'value').'%')->count_all();                     
        }
        $paging = Paging::make($total, $page, $per_page, $per_page, 'admin/logs');
                         
		$this->template->content = View::factory('admin/logs/index')->bind('logs', $logs)->bind('paging', $paging);
	}
    
    
    public function action_indexs()
	{
		if ($total = ORM::factory('log')->count_all())
		{
			$per_page = 12;
            $page = $this->request->param('page', 1);
			$paging = Paging::make($total, $page, 10, 4, 'admin/logs');
			$logs = ORM::factory('log')->order_by('created', 'desc')
				->offset(($page - 1) * $per_page)->limit($per_page)->find_all();
                
            /*
            $page = str_replace('page','',$this->request->param('urlex', 1));
            $page = $this->request->param('page', 1);
            $this->users = ODM::factory('user')->find_all()->sort($sorty)->skip(($page - 1) * $per_page)->limit((int) $per_page);
            $paging = $per_page ? Paging::make($this->users->count(), $page, $per_page, 4, 'users/'.($this->sort ? $this->sort : '')) : NULL;
            */    
    	}
        
        $filters = Model_Filter::instance('user', array('filters' => array('login', 'phone', 'email'),
                                          'orders' => array('login', 'phone', 'email'))
                                          );
                                 
		$this->template->content = View::factory('admin/logs/index')->bind('logs', $logs)->bind('paging', $paging);
	}
    
    
    public static function sort()
    {
       $post = $this->request->post();
       $lift_id = Arr::get($post, 'lift', NULL);
    }
    
    
    public static function search()
    {
        
    }  
 }   