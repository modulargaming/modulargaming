<?php defined('SYSPATH') OR die('No direct script access.');

class Model_User_Notification_Icon extends ORM {

	/**
	 * Create a new notification & log.
	 *
	 * @param User_Model $user
	 * @param string $title
	 * @param string $description
	 * @param array $param
	 */
	public function img()
	{
		return URL::base().'assets/img/notification/icons/'.$this->image;
	}

}
