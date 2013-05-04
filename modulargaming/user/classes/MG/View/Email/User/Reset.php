<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Password reset view.
 *
 * @package    MG/User
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_View_Email_User_Reset extends Abstract_View_Email {

	public $subject = 'Password reset';

	/**
	 * @var Model_User User
	 */
	public $user;

	/**
	 * @var string Token
	 */
	public $token;

	/**
	 * @return string Full url to the token password reset.
	 */
	public function token_link()
	{
		return Route::url('user.reset', array('token' => $this->token), TRUE);
	}

}
