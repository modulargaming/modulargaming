<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_User_Settings extends Abstract_Controller_User {

	protected $protected = TRUE;

	public function action_index()
	{
		$settings = new Settings;

		$settings->add_setting(new Setting_Preferences($this->user));
		$settings->add_setting(new Setting_Profile($this->user));
		$settings->add_setting(new Setting_Account($this->user));

		$this->view = new View_User_Settings;
		$this->view->settings = $settings;
	}

}
