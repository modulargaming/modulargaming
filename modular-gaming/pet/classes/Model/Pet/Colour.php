<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Pet_Colour extends ORM {

	protected $_has_many = array(
		'pets' => array(
			'model' => 'User_Pet',
			'foreign_key' => 'colour_id',
		),
		'species' => array(
			'model' => 'Pet_Specie',
			'through' => 'pet_species_colours',
			'foreign_key' => 'colour_id',
			'far_key' => 'pet_specie_id'		
		)
	);

}
