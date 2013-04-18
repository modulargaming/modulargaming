<?php defined('SYSPATH') OR die('No direct access allowed.');

return array (
	'global' => array(
		'head' => array(
			'css' => array(
				array(
					'name' => 'forum',
					'file' => 'forum.css'
				)
			)
		)
	),

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
);
