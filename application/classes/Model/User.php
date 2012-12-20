<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_User extends Model_Auth_User {

	protected $_belongs_to = array(
		'timezone' => array(
			'model' => 'User_TimeZone',
		),
	);

	static public function user_exists($id)
	{
		$user = ORM::Factory('User', $id);

		return $user->loaded();
	}

} // End User Model
