<?php defined('SYSPATH') OR die('No direct script access.');
/**
 *
 *
 * @package    MG/User
 * @category   Setting
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Setting_Preferences extends Setting {

	public $title = "Preferences";
	public $icon = "icon-wrench";

	public function __construct(Model_User $user)
	{
		parent::__construct($user);

		$timezones = ORM::factory('User_Timezone')
			->find_all();

		$view = new View_User_Settings_Preferences;
		$view->timezones = $timezones->as_array();

		$this->add_content($view);
	}

	/**
	 * Save the user information.
	 *
	 * @param array $post
	 */
	public function save(array $post)
	{
		$this->user->update_user($post, array('timezone_id'));
	}
}
