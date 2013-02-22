<?php defined('SYSPATH') OR die('No direct script access.');

class Model_User_Notification extends ORM {

	protected $_belongs_to = array(
		'log' => array(
			'model' => 'Log',
		),
		'icon' => array(
			'model' => 'User_Notification_Icon',
			'foreign_key' => 'icon',
			'far_key' => 'name'
		)
	);

	/**
	 * Create a new notification & log.
	 *
	 * @param User_Log $log
	 * @param User_Model $user
	 * @param string $title
	 * @param string $icon
	 * @param string $message
	 * @param array $param
	 * @param string $type
	 */
	public static function add_notification(Model_Log $log, Model_User $user, $title, $icon, $message, array $param = array(), $type='info')
	{
		$param['username'] = $log->user->username;

		$values = array(
			'log_id' => $log->id,
			'user_id' => $user->id,
			'title' => $title,
			'message' => __($message, $log->params + $param),
			'icon' => $icon,
			'type' => $type
		);

		return ORM::factory('User_Notification')
			->values($values)
			->create();
	}

}
