<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	'frontend' => array(
		'body' => array(
			'script' => array(
				'user' => array(
					'file' => 'assets/js/user.js'
				)
			)
		)
	),
	'backend' => array(
		'body' => array(
			'script' => array(
				'user.user' => array(
					'file' => 'assets/js/admin/user/user.js'
				),
				'user.role' => array(
					'file' => 'assets/js/admin/user/role.js'
				),
				'user.avatars' => array(
					'file' => 'assets/js/admin/avatar.js'
				)
			)
		)
	)
);
