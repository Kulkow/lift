<?php defined('SYSPATH') or die('No direct access allowed.');

return array
(
	'default' => array
        (
                'type'       => 'MySQLi',
                'connection' => array(
                        'hostname'   => 'localhost',
                        'database'   => 'lift',
                        'username'   => 'root',
                        'password'   => '',
                        //'port'       => NULL,
                        //'socket'     => NULL
                ),
                'table_prefix' => '',
                'charset'      => 'utf8',
                'caching'      => FALSE,
        ),
	'alternate' => array(
		'type'       => 'pdo',
		'connection' => array(
			/**
			 * The following options are available for PDO:
			 *
			 * string   dsn         Data Source Name
			 * string   username    database username
			 * string   password    database password
			 * boolean  persistent  use persistent connections?
			 */
			'dsn'        => 'mysql:host=localhost;dbname=template',
			'username'   => 'xxx',
			'password'   => 'xxx',
			'persistent' => FALSE,
		),
		/**
		 * The following extra options are available for PDO:
		 *
		 * string   identifier  set the escaping identifier
		 */
		'table_prefix' => '',
		'charset'      => 'utf8',
		'caching'      => FALSE,
		'profiling'    => TRUE,
	),
    'postgresql' => array
	(
		'type'       => 'PostgreSQL',
		'connection' => array(
			'hostname'   => 'localhost',
			'username'   => 'postgres',
			'password'   => '',
			'persistent' => FALSE,
			'database'   => 'lift',
		),
		'primary_key'  => '',   // Column to return from INSERT queries, see #2188 and #2273
		'schema'       => '',
		'table_prefix' => '',
		'charset'      => 'utf8',
		'caching'      => FALSE,
	),
);