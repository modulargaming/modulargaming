<?php defined('SYSPATH') OR die('No direct access allowed.');

return array (
	'inventory' => array(
		'pagination' => 20,
		'ajax' => true,
		'consume_show_results' => 'all', // (all|first) show all or the first result the item commands return when consuming an item
	),
	'cookbook' => array(
		'ajax' => true		
	),
	'safe' => array(
		'pagination' => 30
	),
	'user_shop' => array(
		'description_char_limit' => 500, //character length of the user's shop description
		'creation_cost' => 200, //set to 0 or false to disable
		'size' => array( //put a limit on how many items that shop can contain
			'active' => true, 
			'unit_cost' => 100,	
			'unit_size' => 10	
		)	
	)
);
