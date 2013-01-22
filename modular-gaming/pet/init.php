<?php defined('SYSPATH') OR die('No direct script access.');

Route::set('pets', 'pets')
	->defaults(array(
		'directory'  => 'Pet',
		'controller' => 'Index',
		'action'     => 'Index',
	));

Route::set('pet.adopt', 'pet/adopt')
	->defaults(array(
		'directory'  => 'Pet',
		'controller' => 'Adopt',
		'action'     => 'Index',
	));

Route::set('pet.create', 'pet/create')
	->defaults(array(
		'directory'  => 'Pet',
		'controller' => 'Create',
		'action'     => 'Index',
	));

Route::set('pet', 'pet/<name>', array('name' => '[a-zA-Z0-9-_]+'))
	->defaults(array(
		'directory'  => 'Pet',
		'controller' => 'Profile',
		'action'     => 'Index',
	));

//Route::set('pet.admin.index', 'admin/pet(/<action>(/<id>))', array('id' => '[0-9]+'))
Route::set('pet.admin.index', 'admin/pet')
	->defaults(array(
		'directory'  => 'Admin',
		'controller' => 'Pet',
		'action'     => 'Index',
));
Route::set('pet.admin.colour', 'admin/pet/colour(/<action>(/<id>))', array('id' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'Admin/Pet',
		'controller' => 'Colour',
		'action'     => 'Index',
));
Route::set('pet.admin.specie', 'admin/pet/specie(/<action>(/<id>))', array('id' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'Admin/Pet',
		'controller' => 'Specie',
		'action'     => 'Index',
));
//Add link to manage forums in admin
Event::listen('admin.nav_list', function(){
	return array(
			'title' => 'Pet',
			'link'  => URL::site('admin/pet'),
			'icon'  => 'icon-picture'
	);
});
