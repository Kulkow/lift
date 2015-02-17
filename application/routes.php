<?php defined('SYSPATH') or die('No direct script access.');




/**
 * �������
 */
Route::set('admin4', 'admin/event(/<controller>(/page<page>))')
	->defaults(array(
		'directory'		=> 'admin/Event',
		'controller'	=> 'event_type',
		'action'		=> 'index',
	));

Route::set('admin3', 'admin/event(/<controller>(/<action>(/<id>))(/page<page>))')
	->defaults(array(
		'directory'		=> 'admin/Event',
		'controller'	=> 'type',
		'action'		=> 'index',
	));

Route::set('admin1', 'admin(/<controller>(/page<page>))')
	->defaults(array(
		'directory'		=> 'admin',
		'controller'	=> 'layout',
		'action'		=> 'index',
	));
    
Route::set('admin2', 'admin(/<controller>(/<action>(/<id>))(/page<page>))')
	->defaults(array(
		'directory'		=> 'admin',
		'controller'	=> 'layout',
		'action'		=> 'index',
	));

/**
 * ����������� ��������
 */
Route::set('page', '<alias>.html')
	->defaults(array(
		'controller' 	=> 'page',
		'action' 		=> 'view',
	));

/**
 * ��������
 */
Route::set('item', '<controller>/<alias>.html')
	->defaults(array(
		'action' 		=> 'view',
	));

/**
 * ������������
 */
Route::set('user', 'user(/<id>(/<action>))')
	->defaults(array(
		'controller' 	=> 'user',
		'action'     	=> 'index',
	));



    
/**
 * ������
 */
//Route::set('list', '<controller>(/page<page>)(/<alias>)', array('page' => '/[0-9]+/'));
Route::set('list', '<controller>(/page<page>)', array('page' => '/[0-9]+/'));


/**
 * �����
 */
Route::set('default', '(<controller>(/<action>(/<id>)))')
	->defaults(array(
		'controller' 	=> 'index',
		'action'     	=> 'index',
	));
