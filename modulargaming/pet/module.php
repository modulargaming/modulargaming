<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
	'handle' => 'pet',
	'name' => 'Pets',
	'description' => 'Let your users adopt pets',
	'config' => array(
		'pet' => array(
			'type' => 'bootstrap_tabs',
			'id' => 'pet-tabs',
			'entries' => array(
				array(
					'caption' => 'Pets',
					'id' => 'tab-pets',
					'html' => array(
						array(
							'type' => 'number',
							'caption' => 'Limit',
							'info' => 'How many pets can a user own?',
							'name' => 'pet.limit',
						),
						array(
							'type' => 'fieldset',
							'caption' => 'Image',
							'html' => array(
								array(
									'type' => 'number',
									'caption' => 'Width',
									'name' => 'pet.image.width'
								),
								array(
									'type' => 'number',
									'caption' => 'Height',
									'name' => 'pet.image.height'
								)
							)
						)
					)
				),
			)
		)
	)
);
