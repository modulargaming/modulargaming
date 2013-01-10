<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_Dashboard extends Controller_Admin {

	public function action_index()
	{

		if ( ! $this->user->can('Admin_Dashboard_Index') )
		{
			throw HTTP_Exception::factory('403', 'Permission denied to access admin dashboard index ');
		}
		$this->view = new View_Admin_Dashboard;
	}

}
