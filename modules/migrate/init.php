<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Simple Migrations init action, runs every page load
 */
// API v1 route
Route::set('migrate', 'migrate(/<action>(/<id>))')
	->defaults(array(
	'directory' => 'simple',
	'controller' => 'migrations',
	'action' => 'index',
));