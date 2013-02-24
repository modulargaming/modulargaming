<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_User_Index extends Abstract_View_Admin {

	public $title = 'Users';

	/**
	 * @var Model_User[] users to render in the table.
	 */
	public $users;

	public function users()
	{
		$users = array();

		foreach ($this->users as $user)
		{
			$users[] = array(
				'id'         => $user->id,
				'username'   => $user->username,
				'email'      => $user->email,
				'last_login' => Date::format($user->last_login),
				'created'    => Date::format($user->created),
				'links' => array(
					'profile' => Route::url('user.view', array('id' => $user->id)),
					'edit'    => '/admin/user/edit/'.$user->id, // TODO: Use reverse routing!
				),
			);
		}

		return $users;
	}

}
