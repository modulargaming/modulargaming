<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
	'frontend' => array(
		'body' => array(
			'script' => array(
				'pet.create' => array(
					'file' => 'assets/js/pets/create.js'
				)
			)
		)
	),
	'backend' => array(
		'body' => array(
			'script' => array(
				'pet.specie' => array(
					'file' => 'assets/js/admin/pets/list.js'
				),
				'pet.colour' => array(
					'file' => 'assets/js/admin/pets/colour.js'
				)
			)
		)
	)
);