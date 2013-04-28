<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_User_Modal_Password {

	public function csrf()
	{
		return Security::token();
	}

}
