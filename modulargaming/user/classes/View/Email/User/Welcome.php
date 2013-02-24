<?php defined('SYSPATH') OR die('No direct script access.');

class View_Email_User_Welcome extends Abstract_View_Email {

	public $subject = 'Welcome';

	/**
	 * @var Model_User User
	 */
	public $user;

}
