<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
	'frontend' => array(
		'body' => array(
			'script' => array(
				'item.inventory.index' => array(
					'file' => 'assets/js/item/inventory/index.js'
				),
				'item.inventory.view' => array(
					'file' => 'assets/js/item/inventory/view.js'
				),
				'item.cookbook' => array(
					'file' => 'assets/js/cookbook.js'
				)
			)
		)
	)
	/*
	'admin_item' => array(
		'list'   => array(
			'body' => array(
				'js' => array(
					array(
						'name' => 'item.list',
						'file' => 'admin/item/list.js'
					)
				)
			),
		),
		'type'   => array(
			'body' => array(
				'js' => array(
					array(
						'name' => 'item.type',
						'file' => 'admin/item/type.js'
					)
				)
			),
		),
		'shop'   => array(
			'body' => array(
				'js' => array(
					array(
						'name' => 'item.shop',
						'file' => 'admin/item/shops.js'
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
	*/
);
