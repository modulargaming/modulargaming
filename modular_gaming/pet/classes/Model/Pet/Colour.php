<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Pet_Colour extends ORM {

	protected $_has_many = array(
		'pets' => array(
			'model' => 'Pet',
			'foreign_key' => 'race_id',
		),
	);

	static public function colour_exists($id)
	{
		$colour = ORM::factory('Pet_Colour', $id);

		return $colour->loaded();
	}

	static public function colour_free($id)
	{
		$colour = ORM::factory('Pet_Colour', $id);

		return $colour->locked == 0;
	}

}
