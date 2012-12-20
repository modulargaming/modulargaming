<?php defined('SYSPATH') OR die('No direct script access.');

class Date extends Kohana_Date{

	/**
	 * Format timestamp to users format.
	 *
	 * @param $timestamp
	 * @return string
	 */
	public static function format($timestamp)
	{
		$auth = Auth::instance();
		$format = Kohana::$config->load('date.default_format');

		if ($auth->logged_in())
		{
			//$user = $auth->get_user();
			//$format = $user->date_format;
		}

		return strftime($format);
	}

}
