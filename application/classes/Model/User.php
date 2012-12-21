<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_User extends Model_Auth_User {

	protected $_belongs_to = array(
		'timezone' => array(
			'model' => 'User_TimeZone',
		),
	);

	public function rules()
	{
		return Arr::merge(parent::rules(), array(
			'timezone' => array(
				array('Model_User_Timezone::timezone_exists')
			)
		));
	}

	static public function user_exists($id)
	{
		$user = ORM::Factory('User', $id);

		return $user->loaded();
	}

} // End User Model
