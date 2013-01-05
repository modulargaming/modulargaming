<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_Forum extends Controller_Admin {

	public function action_index()
	{
		$this->view = new View_Admin_Dashboard;
	}

}
