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