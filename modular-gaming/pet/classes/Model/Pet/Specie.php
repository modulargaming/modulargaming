<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Pet_Specie extends ORM {

	protected $_has_many = array(
		'pets' => array(
			'model' => 'User_Pet',
			'foreign_key' => 'specie_id',
		),
		'colours' => array(
			'through' => 'pet_species_colours'		
		)
	);

	static public function specie_exists($id)
	{
		$specie = ORM::factory('Pet_Specie', $id);

		return $specie->loaded();
	}

}
