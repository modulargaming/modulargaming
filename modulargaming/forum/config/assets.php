<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
	'frontend' => array(
		'head' => array(
			'style' => array(
				'forum' => array(
					'file'   => 'assets/css/forum.css'
				)
			)
		)
	),
	'backend' => array(
		'body' => array(
			'script' => array(
				'forum.category' => array(
					'file' => 'assets/js/admin/forum/category.js'
				)
			)
		)
	)

	/*
		'admin_forum' => array(
			'category' => array(
				'body' => array(
					'js' => array(
						array(
							'name' => 'forum.category',
							'file' => 'admin/forum/category.js'
						)
					)
				),
			),
		)
	*/
);
