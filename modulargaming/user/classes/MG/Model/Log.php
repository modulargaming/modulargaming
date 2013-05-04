<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Log Model.
 *
 * @package    MG/User
 * @category   Model
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Model_Log extends ORM {

	protected $_created_column = array(
		'column' => 'time',
		'format' => TRUE
	);

	protected $_belongs_to = array('user' => array());

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
