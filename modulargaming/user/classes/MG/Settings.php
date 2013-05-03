<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Settings list
 *
 * @package    MG/User
 * @category   Setting
 * @author     Modular Gaming Team
 * @copyright  (c) 2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Settings {

	/**
	 * @var Setting[]
	 */
	private $settings = array();

	public function add_setting(Setting $setting)
	{
		$this->settings[] = $setting;
	}

	public function get_all()
	{
		return $this->settings;
	}

	/**
	 * Get the setting with the specified id.
	 *
	 * @param integer $id
	 * @return null|Setting
	 */
	public function get_by_id($id)
	{
		foreach ($this->settings as $setting)
		{
			if ($setting->id() == $id)
			{
				return $setting;
			}
		}
		return NULL;
	}

}
