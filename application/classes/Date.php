<?php defined('SYSPATH') OR die('No direct script access.');

class Date extends Kohana_Date{

	/**
	 * Format timestamp to users format.
	 *
	 * @param   integer $timestamp Timestamp to format
	 * @return  string
	 */
	public static function format($timestamp)
	{
		$auth = Auth::instance();
		$format = Kohana::$config->load('date.default_format');

		if ($auth->logged_in())
		{
			$user = $auth->get_user();
			$timezone = $user->timezone->timezone;
			$offset = Date::offset($timezone);
			$timestamp = $timestamp + $offset;
		}

		return strftime($format, $timestamp);
	}

}
