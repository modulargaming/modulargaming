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
		return Validation::factory($post)
			->rule('about', 'max_length', array(':value', '1024'))
			->rule('signature', 'max_length', array(':value', '1024'));
	}

	/**
	 * Save the user information.
	 *
	 * @param array $post
	 */
	public function save(array $post)
	{
		$this->user->set_property('about',     Security::xss_clean(Arr::get($post, 'about')));
		$this->user->set_property('signature', Security::xss_clean(Arr::get($post, 'signature')));
		$this->user->update(); // Save cached_properties.
	}
}
