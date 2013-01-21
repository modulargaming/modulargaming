<?php defined('SYSPATH') OR die('No direct script access.');

Route::set('pets', 'pets')
	->defaults(array(
		'directory'  => 'pet',
		'controller' => 'index',
		'action'     => 'index',
	));

Route::set('pet.adopt', 'pet/adopt')
	->defaults(array(
		'directory'  => 'pet',
		'controller' => 'adopt',
		'action'     => 'index',
	));

Route::set('pet.create', 'pet/create')
	->defaults(array(
		'directory'  => 'pet',
		'controller' => 'create',
		'action'     => 'index',
	));

Route::set('pet', 'pet/<name>', array('name' => '[a-zA-Z0-9-_]+'))
	->defaults(array(
		'directory'  => 'pet',
		'controller' => 'profile',
		'action'     => 'index',
	));

Route::set('pet.admin.colour', 'admin/pet/colour')
	->defaults(array(
		'directory'  => 'Admin/Pet',
		'controller' => 'colour',
		'action'     => 'index',
));
//Add link to manage forums in admin
Event::listen('admin.nav_list', function(){
	return array(
			'title' => 'Pet',
			'link'  => URL::site('admin/pet'),
			'icon'  => 'icon-picture'
	);
});
