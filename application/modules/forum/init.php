<?php defined('SYSPATH') OR die('No direct script access.');

Route::set('forum', 'forum')
	->defaults(array(
		'directory'  => 'forum',
		'controller' => 'index',
		'action'     => 'index',
	));

Route::set('forum/category', 'forum/category/<id>(/<action>)', array('id' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'forum',
		'controller' => 'category',
		'action'     => 'view',
	));

Route::set('forum/topic', 'forum/topic/<id>(/<action>)', array('id' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'forum',
		'controller' => 'topic',
		'action'     => 'view',
	));

Route::set('forum/post', 'forum/post/<id>(/<action>)', array('id' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'forum',
		'controller' => 'post',
		'action'     => 'view',
	));
