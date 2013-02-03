<?php defined('SYSPATH') OR die('No direct script access.');

class View_User_Login extends Abstract_View {

	public $title = 'Login';

	public function links()
	{
		return array(
			'forgot' => Route::url('user.forgot')
		);
	}

}
