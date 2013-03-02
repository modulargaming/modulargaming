<?php defined('SYSPATH') OR die('No direct script access.');
 
class Setting_Preferences extends Setting {

	public $id = "preferences";
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
	 * @return Validation
	 */
	public function get_validation()
	{
		// TODO: Implement get_validation() method.
	}

	/**
	 * Save the user information.
	 */
	public function save()
	{
		// TODO: Implement save() method.
	}
}
