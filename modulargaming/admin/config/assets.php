<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
	'backend' => array(
		'head' => array(
			'style' => array(
				'bootstrap' => array(
					'file'   => 'assets/css/bootstrap.css',
					'weight' => -100
				),
				'bootstrap-responsive' => array(
					'file'   => 'assets/css/bootstrap-responsive.css',
					'weight' => -99
				),
				'bootstrap-whysihtml5' => array(
					'file'   => 'assets/css/bootstrap-wysihtml5.css',
					'weight' => -98
				),
				'bootstrap-notify' => array(
					'file'   => 'assets/css/bootstrap-notify.css',
				),
				'data-tables' => array(
					'file'   => 'assets/css/DT_bootstrap.css',
					'weight' => -25
				),
				'style' => array(
					'file'   => 'assets/css/style.css',
					'weight' => -10
				)
			)
		),
		'body' => array(
			'script' => array(
				'jquery-ie8' => array(
					'file'    => 'http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js',
					'minify'  => FALSE,
					'weight'  => -100,
					'wrapper' => array(
						'<!--[if lt IE 9]><!-->',
						'<!--<![endif]-->'
					)
				),
				'jquery' => array(
					'file'    => 'http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js',
					'minify'  => FALSE,
					'weight'  => -99,
					'wrapper' => array(
						'<!--[if gte IE 9]><!-->',
						'<!--<![endif]-->'
					)
				),
				'bootstrap' => array(
					'file'   => 'assets/js/libs/bootstrap.js',
					'minify'  => FALSE,
					'weight' => -90,
				),
				'wysihtml5' => array(
					'file'   => 'assets/js/libs/wysihtml5.js',
					'minify'  => FALSE,
					'weight' => -89,
				),
				'bootstrap-wysihtml5' => array(
					'file'   => 'assets/js/libs/bootstrap-wysihtml5.js',
					'weight' => -88,
				),
				'bootstrap-notify' => array(
					'file'   => 'assets/js/libs/bootstrap-notify.js'
				),
				'admin.dataTables' => array(
					'file'   => 'assets/js/plugins/jquery.dataTables.js',
					'weight' => -25
				),
				'admin.bootstrap-dataTables' => array(
					'file'   => 'assets/js/plugins/DT_bootstrap.js',
					'weight' => -24
				),
				'admin.crud' => array(
					'file' => 'assets/js/plugins/admin.js'
				)
			)
		)
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
