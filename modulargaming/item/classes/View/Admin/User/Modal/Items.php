<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_User_Modal_Items {

	/**
	 * @var Model_User user to edit.
	 */
	public $user;

	public $game_info = array();

	public function locations()
	{
		$list = array();

		foreach($this->locations as $loc) {
			$list[] = array('value' => $loc['location']);
		}

		return $list;
	}

	public function csrf()
	{
		return Security::token();
	}

}
