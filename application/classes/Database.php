<?php defined('SYSPATH') OR die('No direct script access.');

abstract class Database extends Kohana_Database {
    
    public static $default = 'postgresql';
}