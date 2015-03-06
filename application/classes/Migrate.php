<?php defined('SYSPATH') OR die('No direct script access.');

class Migrate{
      
    const TABLE_NAME = 'migrations';

	/**
	 * Base dir (relative to DOCROOT) where migrations are held
	 *
	 * @since 1.0
	 */
	const BASE_DIR = 'migrations';
    
    public $type = NULL;
    public $db = NULL;
    
    public static $instance = NULL;
    
    public static function instance(){
        $new = new Migrate();
        $new->db = Database::instance();
        $new->type = $new->db->type_driver();
        $new::$instance = $new;
        return $new;
    }
      
    public function is_installed()
	{
		return in_array($this->get_table_name(), Database::instance()->list_tables());
	}
    
    public static function get_table_name()
	{
		return Database::instance()->table_prefix() . self::TABLE_NAME;
	}
    
    public final function install()
	{
		if ($this->is_installed()) {
			throw new Kohana_Exception('migration module is already installed!');
		}

		Database::instance()->begin();

        // Create the table
		DB::query(Database::INSERT, $this->get_install_sql())
			->execute();
        DB::insert(self::TABLE_NAME, array('token', 'created','updated','status'))->values(array('install', time(), time(), 'new'))->execute();    

		Database::instance()->commit();
		return TRUE;
	}
    
    public function get_install_sql()
	{
		if(! $this->type){
		  $this->db = Database::instance();
		  $this->type = $this->db->type_driver();
		}
        echo $this->type;
        switch($this->type){
            case 'MySQLi':
                return 'CREATE TABLE IF NOT EXISTS `' . self::TABLE_NAME . '` (
          `id` int(10) NOT NULL AUTO_INCREMENT,
          `token` varchar(40),
          `created` int(10),
          `updated` int(10),
          `status` varchar(40),
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
            break;
            
            case 'PostgreSQL':
                return 'CREATE TABLE IF NOT EXISTS `' . self::TABLE_NAME . '` (
          id SERIAL integer,
          token varchar(40),
          created integer,
          updated int(10),
          status varchar(40)
        );';
            break;
        }
        return '';
	}
    
    public function last(){
        $last = DB::select('token')->from(self::TABLE_NAME)->sort_by('creared','ASC')->limit(1)->execute()->as_array();
        return Arr::get($last,'id', NULL);
    }
    
    
    public function getnew(){
        $new = [];
        $_news = DB::query(Database::SELECT, "SELECT * from ".self::TABLE_NAME." WHERE status='new' ORDER BY created ASC")->execute()->as_array();
        foreach($_news as $_new){
           $new[$_new['created']] = $_new;
        }
        return $new;
    }
    
     public function gettoken($token = NULL){
        if(! $token){
            return FALSE;
        }
        return DB::select('token','id','created','updated')->from(self::TABLE_NAME)->where('token', '=', $token)->limit(1)->execute()->as_array();
     }
    
    
    public function dir(){
        return __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.self::BASE_DIR.DIRECTORY_SEPARATOR.$this->type.DIRECTORY_SEPARATOR;
    }
    
    public function _create(){
        $time = time();
        $insert = DB::insert(self::TABLE_NAME, array('token', 'created', 'updated','status'))
            ->values(array($time, $time, $time, 'new'))->as_object()->execute();
        if(! empty($insert)){
            return $time; 
        }
        return FALSE;
    }
    
    public function default_file(){
        $default_file = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.self::BASE_DIR.DIRECTORY_SEPARATOR.'default.php';
        if(! file_exists($default_file)){
            echo 'empty';
            return NULL;
        }
        if($content = file_get_contents($default_file,TRUE, NULL)){
            echo $content;
        }else{
            echo 'empty2';
        }
        return $content; 
    }
    
    public function get_file($token = NULL){
        if(! $token){
            return FALSE;
        }
        $file = $this->dir().'Migrate_Tocken'.$token.'.php';
        if(! file_exists($file)){
            return FALSE;
        }
        return $file;
    }
    
    public function save($token, $content = NULL){
        $dir = $this->dir();
        if(! file_exists($dir)){
            mkdir($dir,0775,TRUE);
        }
        echo $file = $dir.'Migrate_Tocken'.$token.'.php';
        file_put_contents($file,$content);
    }
    
    public function file_class($token = NULL){
        return 'Migrate_Tocken'.$token;
    }
    
    public function delete($token = NULL){
        return DB::delete(self::TABLE_NAME)->where('token', '=', $token)->execute();
    }
    
    public function up($token = NULL){
        if($class = $this->get_file($token)){
            $class_name = $this->file_class($token);
            require_once($class);
            $up =  $class_name::action_up();
            if($up){
                echo 'n';
                DB::update(self::TABLE_NAME)->set(array('status' => 'up'))->where('token', '=', $token)->execute();    
            }
            return $up;    
        }else{
            echo 'n';
            return FALSE;
        }
    }
    
    public function down($token = NULL){
        if($class = $this->get_file($token)){
            $class_name = $this->file_class($token);
            require_once($class);
            $down = $class_name::action_down(); 
            if($down){
                DB::update(self::TABLE_NAME)->set(array('status' => 'down'))->where('token', '=', $token)->execute();    
            }
            return $down;   
        }else{
            return FALSE;
        }
    }
            
}

?>