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
		)

		/*

		'global' => array(
			'body' => array(
				'js' => array(
					array(
						'name' => 'create',
						'file' => 'pets/create.js'
					),
				)
			)
		),
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
		*/
	);
