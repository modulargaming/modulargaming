<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_User_View extends Abstract_Controller_User {


	/**
	 * View users profile
	 */
	public function action_index()
	{
		$id = $this->request->param('id');

		$user = ORM::factory('User', $id);

		if ( ! $user->loaded())
		{
			throw HTTP_Exception::Factory('404', 'No such user');
		}

		$this->view = new View_User_Profile;
		$this->view->profile_user = $user;
		$this->view->pets = ORM::factory('User_Pet')->where('user_id', '=', $user->id)->order_by('active', 'desc')->find_all()->as_array();
	}

}
