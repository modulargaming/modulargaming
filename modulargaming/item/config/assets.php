<?php defined('SYSPATH') OR die('No direct access allowed.');

return array (
	'admin_item' => array(
		'list' => array(
			'body' => array(
				'js' => array(
					array(
						'name' => 'item.list',
						'file' => 'admin/item/list.js'
					)
				)
			),
		),
		'type' => array(
			'body' => array(
				'js' => array(
					array(
						'name' => 'item.type',
						'file' => 'admin/item/type.js'
					)
				)
			),
		),
		'recipe' => array(
			'body' => array(
				'js' => array(
					array(
						'name' => 'item.recipe',
						'file' => 'admin/item/recipe.js'
					)
				)
			),
		)
	)
);
