<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(

	'points' => array(
		'initial' => 2000
		),

	'image' => array(
		'width' => 80,
		'height' => 80,
		'format' => array('png'),
		'tmp_dir' => DOCROOT.'assets'.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'items'.DIRECTORY_SEPARATOR
	),
	'npc' => array(
		'image' => array(
			'width' => 200,
			'height' => 200
		),
	),
	'inventory' => array(
		'pagination' => 20,
		'ajax' => TRUE,
		'consume_show_results' => 'all', // (all|first) show all or the first result the item commands return when consuming an item
	),
	'cookbook' => array(
		'ajax' => TRUE
	),
	'safe' => array(
		'pagination' => 30
	),
	'user_shop' => array(
		'description_char_limit' => 500, //character length of the user's shop description
		'creation_cost' => 200, //set to 0 or false to disable
		'log_limit' => 35, //how many sale logs the player can view
		'size' => array( //put a limit on how many items that shop can contain
			'active' => TRUE,
			'unit_cost' => 100, //cost of upgrading to one unit higher
			'unit_size' => 10 //how many items can be stored per unit
		)
	),
	'trade' => array(
		'currency_image' => FALSE,
		'lots' => array(
			'max_results' => 25, //max amount of trade lots per page
			'max_items' => 10, //Max amount of items a user can put up for trade
			'count_amount' => TRUE //count the amount of items(true) or item stacks(false)
		),
		'bids' => array(
			'max_results' => 20, //max amount of bids per page
			'max_items' => 10, //Max amount of items a user can bid
			'count_amount' => TRUE, //count the amount of items(true) or item stacks(false)
			'max_in_stack' => 10 //this is the max amount of items a user can bid(counted from stacks)
		)
	)
);
