<?php defined('SYSPATH') OR die('No direct script access.');

class Model_User_Pet extends ORM {

	protected $_created_column = array(
		'column' => 'created',
		'format' => TRUE,

	);

	protected $_belongs_to = array(
		'specie' => array(
			'model' => 'Pet_Specie',
			'foreign_key' => 'specie_id',
		),
		'colour' => array(
			'model' => 'Pet_Colour',
			'foreign_key' => 'colour_id',
		),
		'user' => array(
			'model' => 'User',
			'foreign_key' => 'user_id',
		),
	);

	protected $_load_with = array(
		'user'
	);

	public static function pet_limit($user_id)
	{
		return ORM::factory('User_Pet')->where('user_id', '=', $user_id)->count_all() < 6;
	}

	public function rules()
	{
		return array(
			'user_id' => array(
				array('Model_User_Pet::pet_limit')
			),
			'name' => array(
				array('not_empty'),
				array('max_length', array(':value', 255)),
				array(array($this, 'unique'), array('name', ':value')),
				array('regex', array(':value', '/^[a-zA-Z0-9-_]++$/iD')),
			),
		);
	}

	public function create_pet($values, $expected)
	{
		$extra_validation = Validation::Factory($values)
			->rule('specie_id', 'Model_Pet_Specie::specie_exists')
			->rule('colour_id', 'Model_Pet_Colour::colour_exists')
			->rule('colour_id', 'Model_Pet_Colour::colour_free');

		return $this->values($values, $expected)
			->create($extra_validation);
	}

}

