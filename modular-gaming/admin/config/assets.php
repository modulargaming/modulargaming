<?php defined('SYSPATH') OR die('No direct access allowed.');

return array (
	'admin' => array(
		'head' => array(
			'css' => array(
				array (
					'name' => 'bootstrap.notify',
					'file' => 'bootstrap-notify.css',
					'location' => 'after',
					'relative' => 'bootstrap'
				)
			)		
		),
		'body' => array(
			'js' => array(
				array(
					'name' => 'mustache',
					'file'  => 'libs/mustache.js',
					'location' => 'after',
					'relative' => 'jquery'
				),
				array(
					'name' => 'bootstrap.notify',
					'file'  => 'plugins/bootstrap-notify.js',
					'location' => 'after',
					'relative' => 'bootstrap'
				),
				array(
					'name' => 'admin.crud',
					'file'  => 'plugins/crud.js'
				)
			)
		),
	)
);
