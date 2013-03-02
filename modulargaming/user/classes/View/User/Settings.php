<?php defined('SYSPATH') OR die('No direct script access.');

class View_User_Settings extends Abstract_View {

	/**
	 * @var Settings
	 */
	public $settings;

	public function navigation()
	{
		$navigation = array();
		foreach ($this->settings->get_all() as $setting)
		{
			$navigation[] = array(
				'id'    => $setting->id,
				'title' => $setting->title,
				'icon'  => $setting->icon
			);
		}
		return $navigation;
	}

	public function settings()
	{
		return $this->settings->get_all();
	}



}