<?php defined('SYSPATH') OR die('No direct script access.');

class View_User_Dashboard extends Abstract_View_User {

	public $title = 'Dashboard';

	protected function get_breadcrumb()
	{
		
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Dashboard',
				'href'  => Route::url('user.dashboard')
			)
		));
	}

}
