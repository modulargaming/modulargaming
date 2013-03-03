<?php defined('SYSPATH') OR die('No direct script access.');

class View_User_Settings extends Abstract_View {

	/**
	 * @var Settings
	 */
	public $settings;

	public function settings()
	{
		return $this->settings->get_all();
	}



}