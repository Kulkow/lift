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
       if($field AND $search){
          $this->search = array('field' => $field, 'value' => $search);
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
    
    public static function sort(){
       
    }
    
    
    public static function search()
    {
        
    }  
 }   