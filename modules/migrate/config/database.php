<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Sample database configuration file
 * with Simple Migrations options
 *
 * @since 1.0
 */
return array
(
	'default' => array
	(
		'type'       => 'mysql',
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
			'username'   => 'root',
			'password'   => '',
			'persistent' => FALSE,
		),
		'table_prefix' => '',
		'charset'      => 'utf8',
		'caching'      => FALSE,
		'profiling'    => FALSE,

		// Enables the use of migrations
		'migrations'    => TRUE,

		/**
		 * The method to use when running database migrations
		 *
		 * Possible values (defaults to 1)
		 *
		 * 1 - Use UNIX shell (PHP exec function, mysql shell command) to load the database .sql file
		 * 2 - Explode the file using ';' characters as delimiters and run each command separately (not implemented yet)
		 */
		'migration_method' => 1
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
        
        // Enables the use of migrations
		'migrations'    => TRUE,

		/**
		 * The method to use when running database migrations
		 *
		 * Possible values (defaults to 1)
		 *
		 * 1 - Use UNIX shell (PHP exec function, mysql shell command) to load the database .sql file
		 * 2 - Explode the file using ';' characters as delimiters and run each command separately (not implemented yet)
		 */
		'migration_method' => 1
	),
);