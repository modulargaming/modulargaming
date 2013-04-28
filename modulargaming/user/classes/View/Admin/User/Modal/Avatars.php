<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_User_Modal_Avatars {

	/**
	 * @var Model_User user to edit.
	 */
	public $user;

	public function csrf()
	{
		return Security::token();
	}

}
