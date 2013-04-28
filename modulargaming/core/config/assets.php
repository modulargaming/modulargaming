<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
	'frontend' => array(
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
				'script' => array(
					'file'   => 'assets/js/script.js',
					'weight' => -10
				)
			)
		)
	)
);
