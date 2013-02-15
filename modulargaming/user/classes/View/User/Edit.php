<?php defined('SYSPATH') OR die('No direct script access.');

class View_User_Edit extends Abstract_View {

	public $title = 'Update Profile';
	public $timezones;

	public function timezones()
	{
		$timezones = array();
		$user = $this->player();

		foreach ($this->timezones as $timezone)
		{
			$timezones[] = array(
				'id'       => $timezone->id,
				'name'     => $timezone->name,
				'selected' => $timezone->id == $user['timezone']['id'],
			);
		}

		return $timezones;
	}

}
