<?php defined('SYSPATH') OR die('No direct script access.');

Route::set('forum', 'forum')
	->defaults(array(
		'directory'  => 'forum',
		'controller' => 'index',
		'action'     => 'index',
	));

Route::set('forum/category', 'forum/category/<id>', array('id' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'forum',
		'controller' => 'category',
		'action'     => 'view',
	));

Route::set('forum/topic', 'forum/topic/<id>', array('id' => '[0-9]+'))
	->defaults(array(
		'directory'  => 'forum',
		'controller' => 'topic',
		'action'     => 'view',
	));