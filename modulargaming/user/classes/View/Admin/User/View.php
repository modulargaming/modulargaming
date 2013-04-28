<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_User_View extends Abstract_View_Admin {

	public $title = 'Edit users';

	/**
	 * @var Model_User user to edit.
	 */
	public $user;

	public $game_info = array();

	public function created()
	{
		return Date::format($this->user->created);
	}

	public function last_login()
	{
		return Date::format($this->user->last_login);
	}

	public function roles_available() {
		$list = array();

		foreach($this->roles as $role) {
			if($this->user->has('roles', $role->id) == false)
			{
				$list[] = array(
					'id' => $role->id,
					'name' => $role->name.' | '.$role->description
				);
			}
		}
		return $list;
	}

	public function roles() {
		$list = array();

		foreach($this->user->roles->find_all() as $role) {
			$list[] = array(
				'id' => $role->id,
				'name' => $role->name.' | '.$role->description
			);
		}
		return $list;
	}

	public function titles() {
		$list = array();

		foreach($this->titles as $title) {
			$list[] = array(
				'id' => $title->id,
				'title' => $title->title,
				'linked' => ($this->user->title_id == $title->id)
			);
		}

		return $list;
	}

	public function timezones() {
		$list = array();

		foreach($this->timezones as $timezone) {
			$list[] = array(
				'id' => $timezone->id,
				'name' => $timezone->name,
				'selected' => ($this->user->timezone_id == $timezone->id)
			);
		}

		return $list;
	}
}
