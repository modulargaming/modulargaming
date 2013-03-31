<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_User_Title extends ORM {

	protected $_table_columns = array(
		'id'          => NULL,
		'title'       => NULL,
		'description' => NULL
	);

	public static function title_exists($id)
	{
		$title = ORM::factory('User_Title', $id);

		return $title->loaded();
	}

}
