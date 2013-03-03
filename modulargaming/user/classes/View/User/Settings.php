<?php defined('SYSPATH') OR die('No direct script access.');

class View_User_Settings extends Abstract_View {

	/**
	 * @var Settings
	 */
	public $settings;

	public function settings()
	{
		$settings = array();
		$first = TRUE;
		foreach ($this->settings->get_all() as $setting)
		{
			$settings[] = array(
				'id'     => $setting->id(),
				'title'  => $setting->title,
				'active' => $first,
				'view'   => $setting->view()
			);
			$first = FALSE;
		}
		return $settings;
	}



}