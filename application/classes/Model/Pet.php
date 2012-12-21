<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Pet extends ORM
{

	protected $_has_one = array(
		'user' => array(
			'model' => 'User_Pet',
			'foreign_key' => 'pet_id',
		),
	);
	
	protected $_belongs_to = array(
		'race' => array(
			'model' => 'Pet_Race',
			'foreign_key' => 'race_id',
		),
		'colour' => array(
			'model' => 'Pet_Colour',
			'foreign_key' => 'colour_id',
		),
	);
	
	public function rules()
	{
		return array(
			'name' => array(
				array('not_empty'),
				array('max_length', array(':value', 255)),
			),
		);
	}

	public function create_pet($values, $expected)
	{
		// Validation for id
		$extra_validation = Validation::Factory($values)
			->rule('race_id', 'Model_Pet_Race::race_exists')
			->rule('colour_id', 'Model_Pet_Colour::colour_exists');

		return $this->values($values, $expected)
			->create($extra_validation);
	}



} // End Pet Model