<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Welcome extends Controller_Frontend {

	public function action_index()
	{
		if ($this->auth->logged_in())
		{
			$this->redirect('user');
		}

		$this->view = new View_Welcome;
	}

} // End Welcome
