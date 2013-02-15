<?php defined('SYSPATH') OR die('No direct script access.');

Route::set('assets', 'assets/(<file>)', array('file' => '.+.(?:jpe?g|png|gif|css|js)'))
	->defaults(array(
		'controller' => 'Assets',
		'action'     => 'Index',
	));
