<?php defined('SYSPATH') OR die('No direct script access.');

Route::set('admin', 'admin(/<controller>(/<action>(/<id>)))')
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'dashboard',
		'action'     => 'index',
	));

Route::set('admin.templates', 'assets/js/admin/templates.js')
	->defaults(array(
	'directory'  => 'admin',
	'controller' => 'templates',
	'action'     => 'index',
));
