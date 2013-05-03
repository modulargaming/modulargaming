<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Welcome email view.
 *
 * @package    MG/User
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_View_Email_User_Welcome extends Abstract_View_Email {

	public $subject = 'Welcome';

	/**
	 * @var Model_User User
	 */
	public $user;

}
