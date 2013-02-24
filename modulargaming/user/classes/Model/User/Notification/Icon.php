<?php defined('SYSPATH') OR die('No direct script access.');

class Model_User_Notification_Icon extends ORM {

	/**
	 * Create a new notification & log.
	 *
	 * @return String url to icon
	 */
	public function img()
	{
		return URL::base().'assets/img/notification/icons/'.$this->image;
	}

}
