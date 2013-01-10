<?php defined('SYSPATH') OR die('No direct script access.');


class Controller_User_Dashboard extends Controller_User {

	/**
	 * Show the user dashboard.
	 */
	public function action_index()
	{
		if ( ! $this->auth->logged_in())
		{
			$this->redirect('user/login');
		}
		
		$this->view = new View_User_Dashboard;
	}

}
