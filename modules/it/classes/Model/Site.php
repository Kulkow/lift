<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Site extends ORM
{
	protected $_table_name = 'site';
    
    public $_config = NULL;

	public function __construct()
	{
		parent::__construct();
        $this->_config = Kohana::$config->load('site');
	}

	public $title = NULL;

	public function rules()
	{
		return array(
			'name' => array(
				array('not_empty'),
			),
		);
	}

	public function assign($title = NULL, $keywords = NULL, $description = NULL)
	{
		$this->title = $title;
		$this->keywords = $keywords;
		$this->description = $description;
	}

	public function set($column, $value)
	{
		if ($value)
		{
			parent::set($column, $value);
		}
		return $this;
	}
}