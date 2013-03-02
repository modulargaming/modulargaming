<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Settings list
 *
 * @package    Modular Gaming
 * @category   User
 * @author     Modular Gaming Team
 * @copyright  (c) 2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class Settings {

	/**
	 * @var Setting[]
	 */
	private $settings = array();

	public function add_setting(Setting $setting)
	{
		$this->settings[] = $setting;
	}

	public function get_all()
	{
		return $this->settings;
	}

	/**
	 * Validate the post array, returns TRUE if successful, else returns the array of errors.
	 *
	 * @param array $post
	 * @return array|bool
	 */
	public function validate(array $post)
	{
		$error = FALSE;
		$errors = array();

		foreach ($this->settings as $setting)
		{
			$validation = $setting->get_validation();
			if ( ! $validation->check())
			{
				$error = TRUE;
				$errors = $errors + $validation->errors(); // Correct?
			}
		}

		if ( ! $error)
		{
			return TRUE;
		}
		return $errors;
	}

	public function save(array $post)
	{
		foreach ($this->settings as $setting)
		{
			$setting->save($post);
		}
	}

}
