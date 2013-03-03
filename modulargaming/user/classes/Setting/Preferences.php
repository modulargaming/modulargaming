<?php defined('SYSPATH') OR die('No direct script access.');
 
class Setting_Preferences extends Setting {

	public $title = "Preferences";
	public $icon = "icon-wrench";

	public function __construct(Model_User $user)
	{
		parent::__construct($user);

		$timezones = ORM::factory('user_timezone')
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
