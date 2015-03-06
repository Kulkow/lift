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
    public $tocken = NULL;
    
    protected $_options = array(
		// param name => default value
		'token'   => NULL,
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
        $this->migrate = Migrate::instance();
        $tocken = NULL;
        if(! empty($params['token'])){
           $this->token = $params['token'];
        }
        if(! empty($params['action'])){
            $method  = 'action_'.strtolower($params['action']);
            if(method_exists($this, $method)){
                $this->$method();
            }
        }
        Minion_CLI::write('action:'.$params['action'].' - migrate '.$params['token']);
	}
    
    
    protected function action_install(){
        if($this->migrate){
            $this->migrate->install();
        }
    }
    
    
    protected function action_new(){
        if($this->migrate){
            $new_migrate = $this->migrate->getnew();
            if(! empty($new_migrate)){
                foreach($new_migrate as $_migrate){
                   print_r($_migrate);
                   $up = $this->migrate->up($_migrate['token']);
                   if(! $up){
                        Minion_CLI::write('no up migrate '.$_migrate['token']);
                   } 
                }
            } 
        }
    }
    
    protected function action_up(){
        if($this->migrate){
            if($this->token){
               $up = $this->migrate->up($this->token);
               if(! $up){
                    Minion_CLI::write('no up migrate '.$this->token);
               }else{
                    Minion_CLI::write('up migrate '.$this->token);
               }
            }
        }
    }
    
    protected function action_down(){
        if($this->migrate){
            if($this->token){
               $down = $this->migrate->down($this->token);
               if(! $down){
                    Minion_CLI::write('no up migrate '.$this->token);
               }else{
                    Minion_CLI::write('up migrate '.$this->token);
               }
            }
        }
    }
    
    
    
    protected function action_create(){
        if($this->migrate){
            $token = $this->migrate->_create();
            if($token){
                $sample = $this->migrate->default_file();
                if($sample){
                    $sample = str_replace('Migrate_Tocken','Migrate_Tocken'.$token,$sample);
                    $this->migrate->save($token,$sample);
                    Minion_CLI::write('create migrate '.$token);
                }else{
                    Minion_CLI::write('no file default migrate');
                    $this->migrate->delete($token);
                    return FALSE; 
                }
                
            }
        }
    }
    
    protected function action_history(){
        
    }
    
    
    
 
}