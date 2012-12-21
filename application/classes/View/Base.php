<?php defined('SYSPATH') OR die('No direct script access.');

class View_Base
{
	public $title = 'Welcome';

	public function base_url()
	{
		return url::base();
	}
	
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
		$user = Auth::instance()->get_user()->as_array();
		$user['last_login'] = Date::format($user['last_login']);

		return $user;
	}

	public function hints()
	{
		return Hint::get_once();
	}

	public function debug_toolbar()
	{
		Debugtoolbar::render();
	}

}
