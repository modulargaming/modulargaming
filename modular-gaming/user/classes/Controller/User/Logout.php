<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_User_Logout extends Abstract_Controller_User {


	/**
	 * Sign out the user and redirect him to the frontpage.
	 */
	public function action_index()
	{
		if ($this->request->method() == HTTP_Request::POST)
		{
			Hint::success(Kohana::message('user', 'logout.success'));

			$this->auth->logout();
			$this->redirect('');
		}
		else
		{
			$this->view = new View_User_Logout;
		}
	}

}
