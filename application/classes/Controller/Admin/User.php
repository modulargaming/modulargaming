<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_User extends Controller_Admin {

	public function action_index()
	{
		$users = ORM::Factory('User')
			->find_all();

		$this->view = new View_Admin_User_Index;
		$this->view->users = $users->as_array();
	}

}