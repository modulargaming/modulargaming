<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
	'admin'       => array(
		'head' => array(
			'css' => array(
				array(
					'name'     => 'bootstrap.notify',
					'file'     => 'bootstrap-notify.css',
					'location' => 'after',
					'relative' => 'bootstrap'
				)
			)
		),
		'body' => array(
			'js' => array(
				array(
					'name'     => 'mustache',
					'file'     => 'libs/mustache.js',
					'location' => 'after',
					'relative' => 'jquery'
				),
				array(
					'name'     => 'bootstrap.notify',
					'file'     => 'plugins/bootstrap-notify.js',
					'location' => 'after',
					'relative' => 'bootstrap'
				),
				array(
					'name'      => 'templates',
					'file'      => 'admin/templates.js',
					'location'  => 'after',
					'relative'  => 'mustache',
					'generated' => TRUE
				),
				array(
					'name' => 'admin.crud',
					'file' => 'plugins/admin.js'
				)
			)
		),
	),
	'data_tables' => array(
		'head' => array(
			'css' => array(
				array(
					'name' => 'jquery.dataTables',
					'file' => 'DT_bootstrap.css',
				)
			)
		),
		'body' => array(
			'js' => array(
				array(
					'name'     => 'dataTables',
					'file'     => 'plugins/jquery.dataTables.js',
					'location' => 'after',
					'relative' => 'jquery'
				),
				array(
					'name'     => 'dataTables.bootstrap',
					'file'     => 'plugins/DT_bootstrap.js',
					'location' => 'after',
					'relative' => 'bootstrap'
				)
			)
		),
	),
	'upload'      => array(
		'head' => array(
			'css' => array(
				array(
					'name'     => 'upload.img',
					'file'     => 'bootstrap-fileupload.min.css',
					'location' => 'after',
					'relative' => 'bootstrap'
				)
			)
		),
		'body' => array(
			'js' => array(
				array(
					'name'     => 'upload.img',
					'file'     => 'plugins/bootstrap-fileupload.min.js',
					'location' => 'after',
					'relative' => 'bootstrap'
				)
			)
		),
	)
);
