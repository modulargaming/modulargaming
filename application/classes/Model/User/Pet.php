<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_User_Pet extends ORM {
	
	static public function pet_exists($id)
	{
		$pet = ORM::factory('User_Pet', $id);

		return $pet->loaded();
	}

}
