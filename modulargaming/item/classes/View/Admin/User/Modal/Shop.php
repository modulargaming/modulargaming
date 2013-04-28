<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_User_Modal_Shop {

	/**
	 * @var Model_User user to edit.
	 */
	public $user;

	public $game_info = array();

	public function created()
	{
		return Date::format($this->user->created);
	}

	public function csrf()
	{
		return Security::token();
	}

}
