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
class MG_View_Avatar_Gallery extends Abstract_View {

	/**
	 * @var Model_Avatar[]
	 */
	public $avatars;

	public function avatars()
	{
		$avatars = array();
		$user_avatar = Auth::instance()->get_user()->avatar();

		foreach ($this->avatars as $avatar)
		{
			$avatars[] = array(
				'id'     => $avatar->id,
				'title'  => $avatar->title,
				'image'  => URL::site($avatar->img),
				// $data is protected, so we can't actually get the avatar id, so lets cheat and use the url.
				'active' => (URL::site($avatar->img) == $user_avatar->url())
			);
		}
		return $avatars;
	}

}
