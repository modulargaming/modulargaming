<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * User Notification Model.
 *
 * @package    MG/User
 * @category   Model
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Model_User_Notification extends ORM {

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
	 * @param Model_Log $log
	 * @param Model_User $user
	 * @param string $title
	 * @param string $icon
	 * @param string $message
	 * @param array $param
	 * @param string $type
	 *
	 * @return ORM
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
