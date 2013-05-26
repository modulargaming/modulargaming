<?php defined('SYSPATH') OR die('No direct access allowed.');

return array (
	'modules' => array(

		// This should be the path to this modules userguide pages, without the 'guide/'. Ex: '/guide/modulename/' would be 'modulename'
		'modulargaming' => array(

			// Whether this modules userguide pages should be shown
			'enabled' => TRUE,

			// The name that should show up on the userguide index page
			'name' => 'Modular Gaming',

			// A short description of this module, shown on the index page
			'description' => 'Documentation for Modular Gaming, gaming framework.',

			// Copyright message, shown in the footer for this module
			'copyright' => '&copy; 2012–2013 Modular Gaming Team',
		)
	),

	// Set transparent class name segments
	'transparent_prefixes' => array(
		'MG' => TRUE,
	)
);
