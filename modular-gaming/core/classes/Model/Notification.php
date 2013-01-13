<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Notification extends ORM {

	const SUCCESS = 'success';
	const ALERT   = 'alert';
	const WARNING = 'warning';

	protected $_belongs_to = array(
		'log' => array(
			'model' => 'Log',
		),
	);

	/**
	 * Create a new notification & log.
	 *
	 * @param User_Model $user
	 * @param string $title
	 * @param string $description
	 * @param array $param
	 */
	static function add_notification(Model_User $user, $title, $message, $type, array $param = array())
	{
		$log = Model_Log::add_log($user, $message, $param);

		$values = array(
			'title'   => $title,
			'message' => $message,
			'type'    => $type,
			'user_id' => $user->id,
			'log_id'  => $log->id,
		);

		return ORM::factory('Notification')
			->values($values)
			->create();
	}

}
