<?php defined('SYSPATH') or die('No direct access allowed.');

return array
(
	'default' => array
        (
                'type'       => 'MySQLi',
                'connection' => array(
                        /**
                         *
                         * string   hostname     server hostname
                         * string   database     database name
                         * string   username     database username
                         * string   password     database password
                         * string   port         server port
                         * string   socket       connection socket
                         *
                         */
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
     'default_old' => array
	(
		'type'       => 'MySql',
		'connection' => array(
			/**
			 * The following options are available for MySQL:
			 *
			 * string   hostname     server hostname, or socket
			 * string   database     database name
			 * string   username     database username
			 * string   password     database password
			 * boolean  persistent   use persistent connections?
			 * array    variables    system variables as "key => value" pairs
			 *
			 * Ports and sockets may be appended to the hostname.
			 */
			'hostname'   => 'localhost',
			'database'   => 'lift',
            'password'   => '',
            'username'   => 'root',
			//'username'   => 'rcc',
			//'password'   => 'A1DP9cOD',
			'persistent' => FALSE,
		),
		'table_prefix' => '',
		'charset'      => 'utf8',
		'caching'      => FALSE,
		'profiling'    => TRUE,
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
    'mssql' => array(
        'type'       => 'Mssql',
        'connection' => array(
            /**
             * The following options are available for PDO:
             *
             * string   dsn
             * string   username
             * string   password
             * boolean  persistent
             * string   identifier
             */
            //'dsn'        => 'dblib:host=hostname;dbname=database',
            'dsn'        => 'dblib:host=85.234.37.68:51433;dbname=Bonus',
            'username'   => 'ReadBD',
            'password'   => 'ReadBonus@34',
            'persistent' => FALSE,
        ),
        'table_prefix' => '',
        'charset'      => FALSE,
        'caching'      => FALSE,
        'profiling'    => TRUE,
    ),
);