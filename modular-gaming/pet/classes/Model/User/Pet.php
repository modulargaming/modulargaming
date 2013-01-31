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

	public function rules()
	{
		return array(
			'user_id' => array(
				array('Valid_Pet::limit', array(':value'))
			),
			'name' => array(
				array('not_empty'),
				array('max_length', array(':value', 255)),
				array(array($this, 'unique'), array('name', ':value')),
				array('regex', array(':value', '/^[a-zA-Z0-9-_]++$/iD')),
			),
			'specie_id' => array(
				array('Valid_Pet::specie_exists', array(':value'))
			),
			'colour_id' => array(
				array('Valid_Pet::colour_exists', array(':value')),
				array('Valid_Pet::colour_available', array(':value', ':model'))
			)
		);
	}

	public function create_pet($values, $expected)
	{
		$extra_validation = Validation::Factory($values)
			->rule('colour_id', 'Valid_Pet::colour_free', array(':value'));

		return $this->values($values, $expected)
			->create($extra_validation);
	}

	public function img() {
		return URL::base() . 'assets/img/pets/'.$this->specie->dir.'/'.$this->colour->image;
	}
}

