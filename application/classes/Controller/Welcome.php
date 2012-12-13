<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Welcome extends Controller_Frontend {

	public function action_index()
	{
		$this->view = new View_Welcome;
	}

} // End Welcome
