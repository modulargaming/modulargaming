<?php defined('SYSPATH') OR die('No direct script access.');

return 	array(
	'modules' => 
		array(
		'mg' => 
			array(
			'core' => MGPATH.'core',
				'game'    => MGPATH.'game',
				'forum'   => MGPATH.'forum',
				'item'    => MGPATH.'item',
				'pet'     => MGPATH.'pet',
				'message' => MGPATH.'message',
				'user'    => MGPATH.'user',
				'admin'   => MGPATH.'admin',
		),
		'mod' => 
			array(
				'acl'           => MODPATH.'acl',        // Access control
				'auth'          => MODPATH.'auth',       // Basic authentication
				'asset-merger'  => MODPATH.'asset-merger',
				'cache'         => MODPATH.'cache',      // Caching with multiple backends
				'database'      => MODPATH.'database',   // Database access
				'datatables'      => MODPATH.'datatables',
				'debug-toolbar' => MODPATH.'debug-toolbar',
				'email'         => MODPATH.'email',      // Email manipulation
				'image'         => MODPATH.'image',      // Image manipulation
				'kostache'      => MODPATH.'kostache',   // Mustache template system
				'minion'        => MODPATH.'minion',     // CLI Tasks
				'task-migrations'    => MODPATH.'tasks-migrations',
				'orm'           => MODPATH.'orm',        // Object Relationship Mapping
				'paginate'      => MODPATH.'paginate', // Pagination
				'purifier'      => MODPATH.'purifier',   // HTML Purifier
				'unittest'      => MODPATH.'unittest',   // Unit testing
				'userguide'     => MODPATH.'userguide',  // User guide and API documentation
		),
	),
	'currency' => 'points',
	'initial_points' => '2000',
	'site' => 
		array(
		'status' => 'open',
		'message' => '',
		'route' => '',
	),
	'google_drive' => 
		array(
		'client_id' => '892642853271.apps.googleusercontent.com',
		'client_secret' => 'Lbrzh38zRAlcY2zHX6MeJuet',
		'records_per_file' => '200',
	),
);