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
	),
	'backend' => array(
		'body' => array(
			'script' => array(
				'item.list' => array(
					'file' => 'assets/js/admin/item/list.js'
				),
				'item.type' => array(
					'file' => 'assets/js/admin/item/type.js'
				),
				'item.shops' => array(
					'file' => 'assets/js/admin/item/shops.js'
				),
				'item.recipe' => array(
					'file' => 'assets/js/admin/item/recipe.js'
				)
			)
		)
	)
);
