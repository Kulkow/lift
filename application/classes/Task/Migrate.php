<?php defined('SYSPATH') or die('No direct script access.');
 
/**
 * Its a test task class
 *
 * @author biakaveron
 * 
 * a
 */
class Task_Migrate extends Minion_Task {
 
	public $migrate = NULL;
    public $id = NULL;
    
    protected $_options = array(
		// param name => default value
		'migrate'   => NULL,
        'action'   => NULL,
	);
    /**
	 * Test action
	 *
	 * @param array $params
	 * @return void
	 */
	protected function _execute(array $params)
	{
		//require_once(__DIR__.'/')
        $this->migrate = Migrate::instance();
        $id = NULL;
        if(! empty($params['migrate'])){
           $this->id = $params['migrate'];
        }
        if(! empty($params['action'])){
            $method  = 'action_'.strtolower($params['action']);
            if(method_exists($this, $method)){
                $this->$method();
            }
        }
        Minion_CLI::write('action:'.$params['action'].' - migrate '.$params['migrate']);
        //$tocken = 
	}
    
    
    protected function action_install(){
        if($this->migrate){
            $this->migrate->install();
        }
    }
    
    protected function action_up($ip = NULL){
        
    }
    
    protected function action_down($ip = NULL){
        
    }
    
    protected function action_new(){
        
    }
    
    protected function action_add(){
        
    }
    
    protected function action_history(){
        
    }
    
    
    
 
}