<?php defined('SYSPATH') OR die('No direct access allowed.');

return array (

	'frontend' => array(
		array('style', 'assets/css/forum.css', 'head'),
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
