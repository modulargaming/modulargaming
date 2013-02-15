<?php defined('SYSPATH') OR die('No direct script access.');


class Controller_User_Dashboard extends Abstract_Controller_User {

	/**
	 * Show the user dashboard.
	 */
	public function action_index()
	{
		if ( ! $this->auth->logged_in())
		{
			$this->redirect(Route::get('user.login')->uri());
		}

		$this->user->set_property('avatar', array(
			'driver' => 'gravatar',
		));
		$this->user->save();
		
		$this->view = new View_User_Dashboard;
	}

}
