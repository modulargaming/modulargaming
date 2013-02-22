<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Log extends ORM {

	protected $_created_column = array(
		'column' => 'time',
		'format' => TRUE
	);

	protected $_serialize_columns = array('params');

	public static function add_log(Model_User $user, $alias, $message, array $params = array(), $type="app")
	{
		$values = array(
			'alias'    => $alias,
			'message'  => $message,
			'user_id'  => $user->id,
			'agent'    => Request::user_agent(array('browser', 'platform')),
			'ip'	   => Request::$client_ip,
			'location' => Request::current()->uri(),
			'type' 	   => $type,
			'params'   => $params,
		);

		return ORM::factory('Log')
			->values($values)
			->create();
	}

}
