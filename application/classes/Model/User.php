<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_User extends Model_Auth_User {

	protected $_belongs_to = array(
		'timezone' => array(
			'model' => 'User_Timezone',
		),
	);

	protected $_load_with = array(
		'timezone'
	);

	public function rules()
	{
		return Arr::merge(parent::rules(), array(
			'timezone_id' => array(
				array('not_empty'),
				array('Model_User_Timezone::timezone_exists')
			)
		));
	}

	static public function user_exists($id)
	{
		$user = ORM::Factory('User', $id);

		return $user->loaded();
	}

	public function create_user($values, $expected)
	{
		if ( ! isset($values['timezone_id']))
		{
			$values['timezone_id'] = Kohana::$config->load('date.default_timezone');
		}
		$expected[] = 'timezone_id';

		return parent::create_user($values, $expected);
	}

} // End User Model
