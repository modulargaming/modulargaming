<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Pet extends ORM {

	protected $_has_one = array(
		'user' => array(
			'model' => 'User',
			'foreign_key' => 'id',
		),
	);

	protected $_belongs_to = array(
		'race' => array(
			'model' => 'Pet_Race',
		),
		'colour' => array(
			'model' => 'Pet_Colour',
		)
	);

	public function rules()
	{
		return array(
			'name' => array(
				array('not_empty'),
				array('max_length', array(':value', 255)),
				array(array($this, 'unique'), array('name', ':value')),
			),
		);
	}

	public function create_pet($values, $expected)
	{
		$extra_validation = Validation::Factory($values)
			->rule('race_id', 'Model_Pet_Race::race_exists')
			->rule('colour_id', 'Model_Pet_Colour::colour_exists');

		return $this->values($values, $expected)
			->create($extra_validation);
	}

}

