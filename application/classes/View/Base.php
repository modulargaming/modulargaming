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

	public function user()
	{
		return Auth::instance()->get_user()->as_array();
	}

	public function hints()
	{
		return Hint::get_once();
	}

}
