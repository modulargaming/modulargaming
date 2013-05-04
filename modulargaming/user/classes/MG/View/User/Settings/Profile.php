<?php defined('SYSPATH') OR die('No direct script access.');
/**
 *
 *
 * @package    MG/User
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_View_User_Settings_Profile extends Abstract_View {

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
		$user_avatar = Auth::instance()->get_user()->avatar();

		foreach ($this->avatars as $avatar)
		{
			$avatars[] = array(
				'id'     => $avatar->id,
				'name'   => $avatar->name,
				'view'   => $avatar->edit_view(),
				'active' => ($avatar->id == $user_avatar->id)
			);
		}
		return $avatars;
	}

}
