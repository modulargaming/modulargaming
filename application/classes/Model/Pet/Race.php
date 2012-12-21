<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Pet_Race extends ORM {

	static public function race_exists($id)
	{
		$race = ORM::Factory('Pet_Race', $id);

		return $race->loaded();
	}

}
