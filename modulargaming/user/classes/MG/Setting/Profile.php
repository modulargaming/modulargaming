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
class MG_Setting_Profile extends Setting {

	public $title = "Profile";
	public $icon = "icon-book";

	public function __construct(Model_User $user)
	{
		parent::__construct($user);

		$view = new View_User_Settings_Profile;
		$view->avatars = $this->get_avatar_drivers();

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
		$validation = Validation::factory($post)
			->rule('about', 'max_length', array(':value', '1024'))
			->rule('signature', 'max_length', array(':value', '1024'))
			->rule('avatar-type', array($this, 'valid_avatar_type'), array(':validation', ':field', ':value'));

		// Add avatar specific rules.
		if (isset($post['avatar-type']))
		{
			$avatar = Avatar::factory($this->user, array('driver' => $post['avatar-type']));
			$avatar->validate($validation);
		}

		return $validation;
	}

	/**
	 * Save the user information.
	 *
	 * @param array $post
	 */
	public function save(array $post)
	{
		$avatar = Avatar::factory($this->user, array('driver' => $post['avatar-type']));
		$this->user->set_property('avatar', $avatar->data($post));

		$this->user->set_property('about',     Security::xss_clean(Arr::get($post, 'about')));
		$this->user->set_property('signature', Security::xss_clean(Arr::get($post, 'signature')));
		$this->user->update(); // Save cached_properties.
	}


	/**
	 * Get an instance of all enabled avatar drivers.
	 *
	 * @return Avatar[] enabled avatar instances
	 */
	private function get_avatar_drivers()
	{
		$enabled = Kohana::$config->load('avatar.enabled');
		$avatars = array();

		// Store the users avatar so we won't have to create it multiple times.
		$user_avatar = $this->user->avatar();

		foreach ($enabled as $driver)
		{
			try
			{
				$refl = new ReflectionClass('Avatar_'.$driver);
				$class = $refl->newInstance($this->user, array());

				// The enabled avatar class should have it's values loaded.
				if ($class->id == $user_avatar->id)
				{
					$class = $user_avatar;
				}

				$avatars[] = $class;
			}
			catch (ReflectionException $ex)
			{
				Kohana::$log->add(LOG::ERROR, 'Enabled avatar driver ":driver" does not exist', array(':driver' => $driver));
			}
		}

		return $avatars;
	}

	/**
	 * @param Validation $validation
	 * @param string $field
	 * @param string $value
	 */
	public function valid_avatar_type($validation, $field, $value)
	{
		$avatars = $this->get_avatar_drivers();
		$ids = array();
		foreach ($avatars as $avatar)
		{
			$ids[] = $avatar->id;
		}

		if ( ! in_array($value, $ids))
		{
			$validation->error($field, 'Unknown avatar type');
		}
	}
}
