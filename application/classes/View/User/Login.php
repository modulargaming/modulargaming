<?php defined('SYSPATH') or die('No direct script access.');

class View_User_Login {

	public $title = 'Log In';

	public function csrf()
	{
		return Security::token();
	}

}
