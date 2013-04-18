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

Route::set('forum.topic', 'forum/topic/<id>(/<action>(/<page>))', array('id' => '[0-9]+', 'page' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'Forum',
		'controller' => 'Topic',
		'action'     => 'page',
	));

Route::set('forum.post', 'forum/post/<id>(/<action>)', array('id' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'Forum',
		'controller' => 'Post',
		'action'     => 'view',
	));


Route::set('forum.admin', 'admin/forum')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Forum',
	'action'     => 'index',
));

Route::set('forum.admin.index', 'admin/forum')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Forum',
	'action'     => 'index',
));


Route::set('forum.admin.search', 'admin/forum/search')
	->defaults(array(
	'directory'  => 'Admin',
	'controller' => 'Forum',
	'action'     => 'search',
));

Route::set('forum.admin.category.paginate', 'admin/forum/category/paginate')
	->defaults(array(
	'directory'  => 'Admin/Forum',
	'controller' => 'Category',
	'action'     => 'paginate',
));
Route::set('forum.admin.category.retrieve', 'admin/forum/category/retrieve')
	->defaults(array(
	'directory'  => 'Admin/Forum',
	'controller' => 'Category',
	'action'     => 'retrieve',
));
Route::set('forum.admin.category.delete', 'admin/forum/category/remove')
	->defaults(array(
	'directory'  => 'Admin/Forum',
	'controller' => 'Category',
	'action'     => 'delete',
));
Route::set('forum.admin.category.save', 'admin/forum/category/save')
	->defaults(array(
	'directory'  => 'Admin/Forum',
	'controller' => 'Category',
	'action'     => 'save',
));
Route::set('forum.admin.category.index', 'admin/forum/category')
	->defaults(array(
	'directory'  => 'Admin/Forum',
	'controller' => 'Category',
	'action'     => 'index',
));

/**
Route::set('forum.admin', 'admin/forum(/<action>(/<id>))', array('id' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'Admin',
		'controller' => 'Forum',
		'action'     => 'index',
	));
**/

// Add link to manage forums in admin
Event::listen('admin.nav_list', function(){
	return array(
		'title' => 'Forum',
		'link'  => URL::site('admin/forum'),
		'icon'  => 'icon-comment'
	);
});
