<?php defined('SYSPATH') OR die('No direct script access.');
 
class Setting_Profile extends Setting {

	public $title = "Profile";
	public $icon = "icon-book";

	public function __construct(Model_User $user)
	{
		parent::__construct($user);

		$view = new View_User_Settings_Profile;

		$this->add_content($view);
	}

	/**
	 * Get the validation rules for the settings page.
	 *
	 * @param array $post
	 * @return Validation
	 */
	public function get_validation(array $post)
	{
		// TODO: Implement get_validation() method.
	}

	/**
	 * Save the user information.
	 *
	 * @param array $post
	 */
	public function save(array $post)
	{
		// TODO: Implement save() method.
	}
}
