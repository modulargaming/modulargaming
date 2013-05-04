<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * User Notification Icon Model.
 *
 * @package    MG/User
 * @category   Model
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Model_User_Notification_Icon extends ORM {

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
