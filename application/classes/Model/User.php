<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_User extends Model_Auth_User {

	static public function user_exists($id)
	{
		$user = ORM::Factory('User', $id);

		return $user->loaded();
	}

} // End User Model
