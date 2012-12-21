<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Pet_Colour extends ORM {

	static public function colour_exists($id)
	{
		$colour = ORM::Factory('Pet_Colour', $id);

		return $colour->loaded();
	}

}
