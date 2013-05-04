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
class MG_Setting_Account extends Setting {

	public $title = "Account";
	public $icon = "icon-user";

	public function __construct(Model_User $user)
	{
		parent::__construct($user);

		$view = new View_User_Settings_Account;

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
			->rule('password_current', array($this, 'check_current_password'), array(':validation', ':field', ':value'));
	}

	/**
	 * Save the user information.
	 *
	 * @param array $post
	 */
	public function save(array $post)
	{
		$this->user->update_user($post, array(
			'email',
			'password'
		));

		if ( ! empty($post['password']))
		{
			Hint::success('Updated password!');
		}
	}

	/**
	 * Check to ensure the entered password matches the users password.
	 *
	 * @param Validation $validation
	 * @param string $value
	 */
	public function check_current_password($validation, $field, $value)
	{
		if (Auth::instance()->hash($value) !== $this->user->password)
		{
			$validation->error($field, 'Wrong password');
		}
	}
}
