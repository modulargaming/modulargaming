<?php defined('SYSPATH') OR die('No direct script access.');
 
class View_User_Settings_Profile extends Abstract_View {

	/**
	 * @var Avatar[] enabled avatar drivers
	 */
	public $avatars;

	/**
	 * Get the users properties.
	 *
	 * @return array
	 */
	public function properties()
	{
		$user = Auth::instance()->get_user();
		return array(
			'about'     => $user->get_property('about'),
			'signature' => $user->get_property('signature')
		);
	}

	/**
	 * Format the avatar list.
	 * @return array
	 */
	public function avatars()
	{
		$avatars = array();
		foreach ($this->avatars as $avatar)
		{
			$avatars[] = array(
				'id'     => $avatar->id,
				'name'   => $avatar->name,
				'view'   => $avatar->edit_view(),
				'active' => ($avatar->id == Auth::instance()->get_user()->avatar()->id)
			);
		}
		return $avatars;
	}

}
