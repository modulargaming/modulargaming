<?php defined('SYSPATH') OR die('No direct script access.');

class View_User_Logout extends Abstract_View_User {

	public $title = 'Logout';

	protected function get_breadcrumb()
	{
		
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Logout',
				'href'  => Route::url('user.logout')
			)
		));
	}

}
