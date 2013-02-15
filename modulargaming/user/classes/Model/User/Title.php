<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_User_Title extends ORM {

	static public function title_exists($id)
	{
		$title = ORM::factory('User_Title', $id);

		return $title->loaded();
	}

}
