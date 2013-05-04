<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * View for user profile.
 *
 * @package    MG/User
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_View_User_Profile extends Abstract_View_User {

	/**
	 * @var Model_User the profile user
	 */
	public $user;

	/**
	 * @var String html for the tabs.
	 */
	public $tabs;

	public function title()
	{
		return $this->user->username.'\'s Profile';
	}

	public function user()
	{
		$user = $this->user;
		return array(
			'id'         => $user->id,
			'username'   => $user->username,
			'created'    => Date::format($user->created),
			'last_login' => Date::format($user->last_login),
			'post_count' => $user->get_property('forum.posts'),
			'avatar'     => $user->avatar(),
			'title'      => $user->title->title,
			'about'      => $user->get_property('about'),
			'signature'  => $user->get_property('signature')
		);
	}

	protected function get_breadcrumb()
	{
		$user = $this->user;
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => $user->username,
				'href'  => Route::url('user.profile', array('id' => $user->id))
			)
		));
	}

}
