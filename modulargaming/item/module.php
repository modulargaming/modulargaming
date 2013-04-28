<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
	'handle' => 'item',
	'name' => 'Items',
	'description' => 'Adds items to your game, along with outlets',
	'config' => array(
		'items' => array(
			'type' => 'bootstrap_tabs',
			'id' => 'items-tabs',
			'entries' => array(
				array(
					'type' => 'fieldset',
					'caption' => 'Image',
					'id' => 'tab-image',
					'help' => 'Set the item image dimensions',
					'html' => array(
						array(
							'type' => 'number',
							'caption' => 'Width',
							'name' => 'items.image.width',
						),
						array(
							'type' => 'number',
							'caption' => 'Height',
							'name' => 'items.image.height',
						),
						array(
							'type' => 'select',
							'caption' => 'Format',
							'multiple' => 'multiple',
							'name' => 'items.image.format',
							'options' => array(
								'gif' => 'gif',
								'png' => 'png',
								'jpg' => 'jpg'
							),
							'info' => 'Which format can the item images be uploaded in?'
						),
						array(
							'type' => 'text',
							'caption' => 'Temp save location',
							'name' => 'items.image.tmp_dir',
							'info' => 'Item images are stored here before saving the image to the database.'
						)
					)
				),
				array(
					'caption' => 'NPC',
					'id' => 'tab-npc',
					'help' => 'Shop NPC image dimensions',
					'html' => array(
						array(
							'type' => 'number',
							'caption' => 'Width',
							'name' => 'items.npc.image.width',

						),
						array(
							'type' => 'number',
							'caption' => 'Height',
							'name' => 'items.npc.image.height',

						)
					)
				),
				array(
					'caption' => 'Inventory',
					'id' => 'tab-inventory',
					'help' => '',
					'html' => array(
						array(
							'type' => 'number',
							'caption' => 'Pagination',
							'name' => 'items.inventory.pagination',
							'info' => 'How many items are displayed per page?'
						),
						array(
							'type' => 'checkbox',
							'caption' => 'AJAX',
							'name' => 'items.inventory.ajax',
							'info' => 'Use the bundled ajax interface?'
						),
						array(
							'type' => 'select',
							'caption' => 'Feedback',
							'name' => 'items.inventory.consume_show_results',
							'info' => 'Which result do you want the user to see after consuming an item',
							'options' => array(
								'all' => 'Show all results',
								'first' => 'Only show the result of default command'
							)
						)
					)
				),
				array(
					'caption' => 'Cookbook',
					'id' => 'tab-cookbook',
					'help' => '',
					'html' => array(
						array(
							'type' => 'checkbox',
							'caption' => 'AJAX',
							'name' => 'items.cookbook.ajax',
							'info' => 'Use the bundled ajax interface?'
						)
					)
				),
				array(
					'caption' => 'Safe',
					'id' => 'tab-safe',
					'help' => '',
					'html' => array(
						array(
							'type' => 'number',
							'caption' => 'Pagination',
							'name' => 'items.safe.pagination',
							'info' => 'How many items are displayed per page?'
						)
					)
				),
				array(
					'caption' => 'User shop',
					'id' => 'tab-user_shop',
					'help' => '',
					'html' => array(
						array(
							'type' => 'number',
							'caption' => 'Creation cost',
							'name' => 'items.user_shop.creation_cost',
							'info' => 'How much does it cost to open a shop?'
						),
						array(
							'type' => 'number',
							'caption' => 'Description limit',
							'name' => 'items.user_shop.description_char_limit',
							'info' => 'The maximum character length of the user\'s shop description'
						),
						array(
							'type' => 'number',
							'caption' => 'Log limit',
							'name' => 'items.user_shop.log_limit',
							'info' => 'How many sale logs can the player view?'
						),
						array(
							'type' => 'fieldset',
							'caption' => 'Size',
							'html' => array(
								array(
									'type' => 'checkbox',
									'caption' => 'Activate',
									'name' => 'items.user_shop.size.active',
									'info' => 'Whether or not to limit the user\'s shop stock.'
								),
								array(
									'type' => 'number',
									'caption' => 'Unit cost',
									'name' => 'items.user_shop.size.unit_cost',
									'info' => 'The cost of upgrading to one unit higher'
								),
								array(
									'type' => 'number',
									'caption' => 'Unit size',
									'name' => 'items.user_shop.size.unit_size',
									'info' => 'How many items can be stored per unit'
								)
							)
						)
					)
				),
				array(
					'caption' => 'Trades',
					'id' => 'tab-trade',
					'help' => '',
					'html' => array(
						array(
							'type' => 'fieldset',
							'caption' => 'Lots',
							'info' => '',
							'html' => array(
								array(
									'type' => 'number',
									'caption' => 'Pagination',
									'name' => 'items.trade.lots.max_results',

									'info' => 'The maximum amount of trade lots displayed per page.'
								),
								array(
									'type' => 'number',
									'caption' => 'Max items',
									'name' => 'items.trade.lots.max_items',
									'info' => 'The maximum amount of items a user can put up for trade.'
								),
								array(
									'type' => 'checkbox',
									'caption' => 'Item count',
									'name' => 'items.trade.lots.count_amount',
									'info' => 'Count the amount of items for max_items (checked) or the amount of item stacks(unchecked)'
								)
							)
						),
						array(
							'type' => 'fieldset',
							'caption' => 'bids',
							'info' => '',
							'html' => array(
								array(
									'type' => 'text',
									'caption' => 'Currency image',
									'name' => 'items.trade.currency_image',
									'info' => 'When offering points in a bid this image will be shown.',
									'default' => FALSE,
								),
								array(
									'type' => 'number',
									'caption' => 'Pagination',
									'name' => 'items.trade.bids.max_results',
									'info' => 'The maximum amount of bids displayed per page.'
								),
								array(
									'type' => 'number',
									'caption' => 'Max items',
									'name' => 'items.trade.bids.max_items',
									'info' => 'The maximum amount of items a user can bid.'
								),
								array(
									'type' => 'checkbox',
									'caption' => 'Item count',
									'name' => 'items.trade.bids.count_amount',
									'info' => 'Count the amount of items for max_items (checked) or the amount of item stacks(unchecked)'
								),
								array(
									'type' => 'number',
									'caption' => 'Max items per stack',
									'name' => 'items.trade.bids.max_in_stack',
									'info' => 'The max amount of items per stack a user can bid(if count_amount is unchecked)'
								)
							)
						),
					)
				),
			)
		),
		'notify' => array(

		)
	)
);
