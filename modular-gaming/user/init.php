<?php defined('SYSPATH') OR die('No direct script access.');

Route::set('user', 'user(/<controller>(/<action>(/<id>)))')
	->defaults(array(
		'directory'  => 'user',
		'controller' => 'dashboard',
		'action'     => 'index',
));

Route::set('user/view/(<id>)', 'user/view/index(/<id>)', array('id' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'user',
		'controller' => 'view',
		'action'     => 'index',
	));
