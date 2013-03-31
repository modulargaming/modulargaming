<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_User_Timezone extends ORM {

	protected $_table_columns = array(
		'id'       => NULL,
		'timezone' => NULL,
		'name'     => NULL
	);

	public static function timezone_exists($id)
	{
		$timezone = ORM::factory('User_Timezone', $id);

		return $timezone->loaded();
	}

}
