<?php defined('SYSPATH') OR die('No direct script access.');

class View_User_Profile extends View_Base {

	public $profile_user;

	public function title()
	{
		return $this->profile_user['username'] . '\'s Profile';
	}

}
