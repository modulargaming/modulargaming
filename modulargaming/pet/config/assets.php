<?php defined('SYSPATH') OR die('No direct access allowed.');

	return array(
		'admin_pet' => array(
			'specie' => array(
				'body' => array(
					'js' => array(
						array(
							'name' => 'pet.list',
							'file' => 'admin/pets/list.js'
						)
					)
				),
			),
			'colour' => array(
				'body' => array(
					'js' => array(
						array(
							'name' => 'pet.colour',
							'file' => 'admin/pets/colour.js'
						)
					)
				),
			),
		)
	);
