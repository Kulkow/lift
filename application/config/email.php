<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * 	'group' => array(
 *		'template1'	=> 'Subject fo template1',
 *		'template2'	=> array(
 *			'from'	  => array('mail@domain.ru', 'Name'),
 *			'subject' => 'Subject fo template2',
 *		),
 *	),
 */
/*
return array(
	'default'	=> array(
		'from'		=> 'robot@[domain]',
		'subject'	=> 'Письмо с сайта http://[domain] &laquo;[title]&raquo;',
	),
	'auth'		=> array(
		'registr'	=> 'Регистрация на сайте &laquo;[title]&raquo; (http://[domain])',
		'activate'	=> 'Активация аккаунта на сайте &laquo;[title]&raquo; (http://[domain])',
		'forgot'	=> 'Восстановление пароля на сайте &laquo;[title]&raquo; (http://[domain])',
		'confirm'	=> 'Ваш новый пароль на сайте &laquo;[title]&raquo; (http://[domain])',
	),
);*/

return array(
	'default'	=> array(
		'from'		=> 'robot@rcc.ip-test.info',
		'subject'	=> 'Письмо с сайта http://rcc.ip-test.info &laquo;Компьютерный центр&raquo;',
	),
	'auth'		=> array(
		'registr'	=> 'Регистрация на сайте &laquo;Компьютерный центр&raquo; (http://rcc.ip-test.info)',
		'activate'	=> 'Активация аккаунта на сайте &laquo;Компьютерный центр&raquo; (http://rcc.ip-test.info)',
		'forgot'	=> 'Восстановление пароля на сайте &laquo;Компьютерный центр&raquo; (http://rcc.ip-test.info)',
		'confirm'	=> 'Ваш новый пароль на сайте &laquo;Компьютерный центр&raquo; (http://rcc.ip-test.info)',
	),
);