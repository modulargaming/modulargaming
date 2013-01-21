<?php defined('SYSPATH') OR die('No direct script access.');

Route::set('admin', 'admin(/<controller>(/<action>(/<id>)))')
	->defaults(array(
		'directory'  => 'Admin',
		'controller' => 'Dashboard',
		'action'     => 'Index',
	));

Route::set('admin.templates', 'assets/js/admin/templates.js')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Templates',
	'action'     => 'Index',
));
