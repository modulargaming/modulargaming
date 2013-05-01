<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
	'backend' => array(
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
				'admin.dataTables' => array(
					'file'   => 'assets/js/plugins/jquery.dataTables.js',
					'weight' => -25
				),
				'admin.crud' => array(
					'file' => 'assets/js/plugins/admin.js'
				)
			)
		)
	)
);
