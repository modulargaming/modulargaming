<?php defined('SYSPATH') OR die('No direct script access.');

class View_User_Register extends Abstract_View_User {

	public $title = 'Register';

	protected function get_breadcrumb()
	{
		
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Register',
				'href'  => Route::url('user.register')
			)
		));
	}

}
