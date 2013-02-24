<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_User_Settings extends Abstract_Controller_User {

	protected $protected = TRUE;

	public function action_index()
	{
		$this->view = new View_User_Settings;
		//$this->view->timezones = $timezones->as_array();
	}

}
