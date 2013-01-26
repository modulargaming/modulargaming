<?php defined('SYSPATH') OR die('No direct access allowed.');

return array (
	'inventory' => array(
		'pagination' => 20,
		'ajax' => true,
		'consume_show_results' => 'all', // (all|first)
	),
	'cookbook' => array(
		'ajax' => true		
	),
	'user_shop' => array(
		'description_char_limit' => 500,
		'creation_cost' => 200, //set to 0 or false to disable
		'size' => array( //put a limit on how many items that shop can contain
			'active' => true, 
			'unit_cost' => 100,	
			'unit_size' => 10	
		)	
	)
);
