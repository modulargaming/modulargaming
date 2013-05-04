<?php defined('SYSPATH') OR die('No direct script access.');
/**
 *
 *
 * @package    MG/User
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_View_User_Settings_Preferences extends Abstract_View {

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
