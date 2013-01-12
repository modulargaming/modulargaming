<?php defined('SYSPATH') OR die('No direct script access.');

Route::set('user', 'user(/<action>(/<id>))')
->defaults(array(
'controller' => 'user',
'action'     => 'index',
));

//Add link to manage users in admin
Event::listen('admin.nav_list', function(){
	return array(
			'title' => 'User',
			'link'  => URL::site('admin/user'),
			'icon'  => 'icon-user'
	);
});