<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Journal class.
 *
 * @package    MG/Core
 * @category   Journal
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Journal {
	/**
	 * Create a new log
	 *
	 * @param string     $alias   An identifier to help index logs
	 * @param string     $type    Module name that's logging (e.g. item, pet,..)
	 * @param string     $message A general message describing the action
	 * @param array      $params  Parameters that give insight into the action that has been performed
	 * @param Model_User $user    The user that did an action(defaults to the logged in user if null)
	 *
	 * @return Log
	 */
	static public function log($alias, $type, $message, $params = array(), $user = NULL)
	{
		if ($user == NULL)
		{
			$user = Auth::instance()->get_user();
		}

		$values = array(
			'alias'    => $alias,
			'message'  => $message,
			'user_id'  => $user->id,
			'username' => $user->username,
			'agent'    => implode('-', Request::user_agent(array('browser', 'platform'))),
			'ip'       => Request::$client_ip,
			'location' => Request::current()->uri(),
			'type'     => $type,
			'params'   => $params,
		);

		$log = ORM::factory('Log')
			->values($values)
			->create();

		return new Journal($log);
	}

	protected $_log = null;

	public function __construct($log) {
		$this->_log = $log;
	}

	/**
	 * Send a notification to a user based on a log.
	 *
	 * @param Model_User $user         User instance we'll be notifying
	 * @param string     $notification A string that can be parsed through __()
	 * @param array      $param        Params to parse the notification with (combined with $log->params)
	 * @param string     $type         Type of notification (info, error, success, warning)
	 *
	 * @return User_Notification
	 */
	public function notify($user, $notification, $param = array(), $type = "info")
	{
		$log = $this->_log;

		$notify = Kohana::$config->load('notify.' . $notification);

		$values = array(
			'log_id'  => $log->id,
			'user_id' => $log->user->id,
			'title'   => $notify['title'],
			'message' => __($notify['message'], $log->params, $param),
			'icon'    => $notify['icon'],
			'type'    => $type
		);

		return ORM::factory('User_Notification')
			->values($values)
			->create();
	}
}
