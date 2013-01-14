<?php defined('SYSPATH') OR die('No direct access allowed.');

return array (
	'global' => array(
		'head' => array(
			'css' => array(
				array(
					'name' => 'bootstrap',
					'file' => 'bootstrap.css'
				),
				array(
					'name' => 'bootstrap.responsive',
					'file'  => 'bootstrap-responsive.css'
				),
				array(
					'name' => 'wsihtml5',
					'file'  => 'bootstrap-wysihtml5.css'
				),
				array(
					'name' => 'style',
					'file'  => 'style.css'
				)		
			),
			'js' => array()
		),
		'body' => array(
			'css' => array(),
			'js' => array(
				array(
					'name' => 'jquery',
					'file'  => 'libs/jquery.js'
				),
				array(
					'name' => 'bootstrap',
					'file'  => 'libs/bootstrap.min.js'
				),
				array(
					'name' => 'wysihtml5',
					'file'  => 'plugins/wysihtml5.js'
				),
				array(
					'name' => 'wysihtml.bootstrap',
					'file'  => 'plugins/bootstrap-wysihtml5.js'
				),
				array(
					'name' => 'script',
					'file'  => 'script.js'
				)
			)
		),
	)
);
