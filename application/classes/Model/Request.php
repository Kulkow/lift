<?php defined('SYSPATH') or die('No direct script access.');

/**
* Модель запроса лифта
*/

class Model_Request extends ORM {
    
    const REQUEST_NEW = 0;
    const REQUEST_CLOSE = 1;
    const REQUEST_DEFENDER = 2;
    
    /**
    * fields
    * level - этаж на котором находиться лифт
    * status - соостояние в котором находиться лифт
    * number - номер лифта в доме. 
    */
    
    public function __construct($id = NULL){
		parent::__construct($id);
		if (mt_rand(1, 100) === 1)
		{
			$this->delete_expired();
		}
	}

	/**
	 * Deletes garbage.
	 *
	 * @return  ORM
	 */
	public function delete_expired()
	{
		$expired = time() - 10 * 60;
		DB::delete($this->_table_name)
			->where('status', '=', self::REQUEST_CLOSE)
            ->or_where_open()->where('status', '=',self::REQUEST_NEW)->and_where('created', '<',$expired)->or_where_close()
			->execute($this->_db);

		return $this;
	}

    protected $_table_name = 'request';
    
    protected $_belongs_to = array(
    		'lift'		=> array(
    			'model'		=> 'lift',
    		),
            'house'		=> array(
    			'model'		=> 'house',
    		),
            'user'		=> array(
    			'model'		=> 'user',
    		),
    );
        
    public function rules(){
		return array();
	}
    
    public function filter(){
        $this->created = time();
        $this->ip = Request::$client_ip;
        return $this;
	}
    
    public function close(){
        if($this->loaded()){
            $this->status = self::REQUEST_CLOSE;
            $this->save();
        }
        return FALSE;
    }
    
    
    public function defender($house_id = NULL){
        if(! $house_id){
            return FALSE;
        }
        $request = [];
        $rs = DB::select('*')->from($this->_table_name)->where('status', '=', self::REQUEST_DEFENDER)
            ->and_where('house_id', '=', $house_id)->as_object('Model_Request')->execute($this->_db);
        foreach($rs as $_rs){
            $request[$_rs->level] = $_rs;
        }    
        return $request;
    }
}
 