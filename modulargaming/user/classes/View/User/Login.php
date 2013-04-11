<?php defined('SYSPATH') OR die('No direct script access.');

class View_User_Login extends Abstract_View_User {

	public $title = 'Login';

	public function links()
	{
		return array(
			'forgot' => Route::url('user.reset')
		);
	}

	protected function get_breadcrumb()
	{
		
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Login',
				'href'  => Route::url('user.login')
			)
		));
	}

}
