<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Log extends ORM {

	protected $_created_column = array(
		'column' => 'time',
		'format' => TRUE
	);

	protected $_has_one = array(
		'notification' => array(
			'model' => 'Notification',
		),
	);

	public static function add_log(Model_User $user, $message, array $params = array())
	{
		$request = Request::current();

		$values = array(
			'message'  => $message,
			'user_id'  => $user->id,
			'location' => $request->uri(),
			'params'   => serialize($params),
		);

		return ORM::factory('Log')
			->values($values)
			->create();
	}

}