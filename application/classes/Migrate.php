<?php defined('SYSPATH') OR die('No direct script access.');

class Migrate{
      
    const TABLE_NAME = 'migrations';

	/**
	 * Base dir (relative to DOCROOT) where migrations are held
	 *
	 * @since 1.0
	 */
	const BASE_DIR = 'application/migrations/';
    
    public $type = NULL;
    public $db = NULL;
    
    public static function instance(){
        $new = new Migrate();
        $new->db = Database::instance();
        $new->type = $new->db->type_driver();
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
        
    }
            
}

?>