<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	'global' => array(
		'body' => array(
			'js' => array(
				array(
					'name' => 'user',
					'file' => 'user.js'
				),
				array(
					'name' => 'user-settings',
					'file' => 'user/settings.js'
				)
			)
		)
	),
	'admin_user' => array(
		'body' => array(
			'js' => array(
				array(
					'name' => 'user',
					'file' => 'admin/user.js'
				),
			),
		),
	)
);