<?php defined('SYSPATH') OR die('No direct script access.');

class View_Base
{
	public $title = 'Welcome';

	public function csrf()
	{
		return Security::token();
	}

	public function logged_in()
	{
		return Auth::instance()->logged_in();
	}

}
