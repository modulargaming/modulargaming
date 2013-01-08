<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_User extends Controller_Admin {

	public function action_index()
	{

		if ( ! $this->user->can('Admin_User_Index') )
		{
			throw HTTP_Exception::factory('403', 'Permission denied to access admin user index');
		}

		$users = ORM::factory('User')
			->find_all();

		$this->view = new View_Admin_User_Index;
		$this->view->users = $users->as_array();
	}

	public function action_view()
	{

		if ( ! $this->user->can('Admin_User_View') )
		{
			throw HTTP_Exception::factory('403', 'Permission denied to access admin user view');
		}

		$id = $this->request->param('id');

		$user = ORM::factory('User', $id);

		if ( ! $user->loaded())
		{
			throw HTTP_Exception::factory('404', 'No such user');
		}


	}

}
