<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Routes for Forum.
 */
Route::set('forum', 'forum')
	->defaults(array(
		'directory'  => 'Forum',
		'controller' => 'Index',
		'action'     => 'index',
	));

Route::set('forum.category', 'forum/category/<id>(/<action>(/<page>))', array('id' => '[0-9]+', 'page' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'Forum',
		'controller' => 'Category',
		'action'     => 'page',
	));

Route::set('forum.topic', 'forum/topic/<id>(/<action>)', array('id' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'Forum',
		'controller' => 'Topic',
		'action'     => 'view',
	));

Route::set('forum.post', 'forum/post/<id>(/<action>)', array('id' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'Forum',
		'controller' => 'Post',
		'action'     => 'view',
	));


Route::set('forum.admin', 'admin/forum(/<action>(/<id>))', array('id' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'Admin',
		'controller' => 'Forum',
		'action'     => 'index',
	));

//Add link to manage forums in admin
Event::listen('admin.nav_list', function(){
	return array(
		'title' => 'Forum',
		'link'  => URL::site('admin/forum'),
		'icon'  => 'icon-comment'
	);
});
