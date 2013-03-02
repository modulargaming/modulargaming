<?php defined('SYSPATH') OR die('No direct script access.');
 
class View_User_Settings_Preferences extends Abstract_View {

	/**
	 * @var array
	 */
	public $timezones;

	public function timezones()
	{
		$timezones = array();
		$user = $this->player();

		foreach ($this->timezones as $timezone)
		{
			$timezones[] = array(
				'id'       => $timezone->id,
				'name'     => $timezone->name,
				'selected' => $timezone->id == $user['timezone_id'],
			);
		}

		return $timezones;
	}

}
